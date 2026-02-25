<?php

namespace App\Http\Controllers\Produce;

use App\Http\Controllers\Controller;
use App\Models\Cooperative;
use App\Models\Farm;
use App\Models\Produce;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\Crops;
use App\Http\Resources\CropResource;
use App\Models\CropType;
use App\Http\Resources\CropTypeResource;
use App\Models\ProcessMethod;
use App\Http\Resources\ProcessMethodResource;
use App\Models\CropGrade;
use App\Http\Resources\CropGradeResource;
use App\Models\CooperativeFarmer;
use App\Services\FarmerVerificationService;
use App\Models\FarmerBatchVerification;
use App\Http\Resources\FarmerFullDetailsResource;
use App\Http\Resources\ProduceResource;
use App\Http\Resources\FarmersTableSummaryResource;
use App\Http\Resources\FarmResource;
use App\Http\Resources\CommodityResource;
use App\Models\Commodity;
use App\Models\CommodityFarm;
use App\Models\Batch;
use App\Models\BatchActionList;
use App\Models\ChainBatch;
use App\Models\ChainBlock;
use App\Services\Payments\PaymentService;
use App\Models\CommodityPayment;
use App\Http\Resources\CommodityPaymentResource;


class ProduceController extends Controller
{
/**
 * Display a listing of the resource.
 */
public function index()
{
$cooperativeId = Cooperative::where('user_id', auth()->id())->value('id');
$produces = Commodity::where('cooperative_id', $cooperativeId)->get();
$listedCount = Commodity::where('cooperative_id', $cooperativeId)
->where('status', 'listed')
->count();
$soldCount = Commodity::where('cooperative_id', $cooperativeId)
->where('status', 'sold')
->count();

$totalQuantity = Commodity::where('cooperative_id', $cooperativeId)->sum('weight');

return Inertia::render('ProducePage', [
'title' => 'Add Produce',
'produces' => CommodityResource::collection($produces),
'listed_count' => $listedCount,
'sold_count' => $soldCount,
'total_quantity' => $totalQuantity,
]);

}







public function batchListed(Request $request)
{

$user = $request->user();
$batches = Batch::query()
->where('owner_id', auth()->id())
->where('is_on_chain', 1)
->latest()
->get()
->map(function (Batch $batch) use ($user) {
return [
'id' => $batch->id,
'batch_number' => $batch->batch_code,
'status' => $batch->status,
'grade' => $batch->grade,
'weight' => $batch->weight,
'created_at' => $batch->created_at?->toDateTimeString(),
'listed_at' => $batch->created_at?->toDateString(),
'commodity_id' => null,
'commodity_names' => $batch->commodity_name ? [$batch->commodity_name] : [],
'commodity_count' => $batch->commodity_name ? 1 : 0,
'seller_name' => trim(($user?->fname ?? '') . ' ' . ($user?->lname ?? '')),
'latest_block_hash' => null,
'chain_height' => null,
'ask_price' => null,




];
	});

	$batchActionList = BatchActionList::query()
	->where('name', '!=', 'created')
	->orderBy('name')
	->get(['id', 'name']);


//get the latest block for each batch and attach the hash and chain height to the batch data


return Inertia::render('BatchListed', [
	'batches' => $batches,
	'batch_action_list' => $batchActionList,

]);
}

















/**
 * Store a newly created resource in storage.
 */
public function store(Request $request)
{

// Validate the incoming request data

$validated = $request->validate([
'crop_name' => ['required', 'string', 'max:255'],
'crop_type' => ['required', 'string', 'max:255'],
'quantity' => ['required', 'numeric', 'min:0'],
'price' => ['required', 'numeric', 'min:0'],
'date_of_harvest' => ['required', 'date'],
'crop_grade' => ['required', 'string', 'max:255'],
'verification_id' => ['required', 'string'],
'farm' => ['required', 'array', 'min:1'],
'farm.*' => ['integer', 'exists:farms,id'],
]);

//check if the verification code is valid

$validity = FarmerVerificationService::check_id_validity($validated['verification_id']);
if ($validity['status'] == false) {
return redirect()->route('cooperative.produce.create')->with('success', ['status' => false, 'message' => 'Farmer validation code expired, try again.']);
}

//get cooperative id
$cooperativeId = Cooperative::where('user_id', auth()->id())->value('id');
$verification = FarmerBatchVerification::where('verification_id', $validated['verification_id'])->first();

if (!$verification) {
throw ValidationException::withMessages([
'verification_id' => 'Invalid verification code. Please verify farmer details again.',
]);
}

//associate the produce with the farm

$farmIds = Farm::query()
->whereIn('id', $validated['farm'])
->where('cooperative_farmer_id', $verification->cooperative_farmers_id)
->pluck('id')
->values();

if ($farmIds->isEmpty()) {
throw ValidationException::withMessages([
'farm' => 'Select at least one valid farm for the verified farmer.',
]);
}

$produce = Commodity::create([
'cooperative_id' => $cooperativeId,
'commodity_name' => $validated['crop_name'],
'commodity_type' => $validated['crop_type'],
'grade' => $validated['crop_grade'],
'weight' => $validated['quantity'],
'harvest_date' => $validated['date_of_harvest'],
]);

//associate the produce with the farm

foreach($farmIds as $farm){
CommodityFarm::create([
'commodity_id'=>$produce->id,
'farm_id'=>$farm
]);
}

//prepare payment data
$data=[
'commodity_id'=>$produce->id,
'buyer_id'=>$request->user()->id,
'quantity'=>$validated['quantity'],
'unit_price'=>$validated['price'],
'notes'=>'Payment pending for commodity with ID: '.$produce->id
];

//payment is being processed in the background, so we can return the produce details immediately without waiting for payment confirmation

PaymentService::commodity_payment($data);
$verification?->update(['status' => 'expired']);


return redirect()->route('cooperative.batch.show', ['id' => $produce->id])->with('success', 'Produce saved successfully.');

}






/**
 * Display the specified resource.
 */
public function show(Request $request)
{

$id = $request->segment(3);
$produce = Commodity::query()
->with([
'farms.farmer',
'payments' => fn ($query) => $query
->with('buyer:id,fname,lname,email')
->latest(),
])
->findOrFail($id);

$cooperativeId = Cooperative::where('user_id', auth()->id())->value('id');

// Resolve farmer via commodity_farms, now that commodities no longer carry farm_id.
$farmer = $produce->farms
->pluck('farmer')
->first(function ($candidate) use ($cooperativeId) {
return $candidate && (int) $candidate->cooperative_id === (int) $cooperativeId;
});

// Get the latest payment for the produce
$payment = CommodityPayment::query()
->where('commodity_id', $produce->id)
->with('buyer:id,fname,lname,email')
->latest()
->first();

return Inertia::render('BatchShowPage', [
'produce' => new CommodityResource($produce),
'farmer' => $farmer ? new FarmerFullDetailsResource($farmer) : null,
'payment' => $payment ? new CommodityPaymentResource($payment) : null,
'available_farms' => Farm::query()
->select('id', 'farm_name')
->whereHas('farmer', function ($query) use ($cooperativeId) {
$query->where('cooperative_id', $cooperativeId);
})
->orderBy('farm_name')
->get(),
'origin_farm_ids' => $produce->farms->pluck('id')->values(),

]);

}





/**
 * Update the specified resource in storage.
 */
public function update(Request $request, string $id)
{
//





}

/**
 * Remove the specified resource from storage.
 */
public function destroy(string $id)
{
//




}







public function create(Request $request)
{

$uid=$request->user()->id;
$cooperative = Cooperative::where('user_id', $uid)->first();
return $cooperative;
return Inertia::render('ProduceCreateAfterVerification', [

]);
}





public function create_batch(Request $request)
{

//get url segment 4
$segment = $request->segment(4);
$verification = FarmerBatchVerification::where('verification_id',$segment)->first();
if(!$verification){
return redirect()->route('cooperative.produce.create')->with('success',['status'=>false,'message'=>'Could not verify farmer details.']);
}


$cooperativeId = Cooperative::where('user_id', auth()->id())->value('id');
$crops=Crops::get();
$crop_type=CropType::get();
$process_method=ProcessMethod::get();
$grade=CropGrade::get();
$farmer_id=$verification->cooperative_farmers_id;
$farmer=CooperativeFarmer::where('id',$farmer_id)->first();
$farms =Farm::select('id','farm_name')->where('cooperative_farmer_id',$farmer->id)->get();

return Inertia::render('ProduceCreateAfterVerification', [
'title' => 'Add Produce',
'farms' => $farms,
'crops'=>CropResource::collection($crops),
'crop_type'=>CropTypeResource::collection($crop_type),
'process_method'=>ProcessMethodResource::collection($process_method),
'crop_grade'=>CropGradeResource::collection($grade),
'farmer'=> new FarmersTableSummaryResource($farmer),


]);
}







public function store_verification(Request $request)
{
$validated = $request->validate([
'last_name' => ['required', 'string', 'max:255'],
'phone_number' => ['required', 'string', 'regex:/^[0-9+()\\-\\s]{7,20}$/'],
]);

$cooperative=Cooperative::where('user_id',$request->user()->id)->first()->id;
$farmer = CooperativeFarmer::where('last_name', $validated['last_name'])
->where('phone_number', $validated['phone_number'])
->where('cooperative_id',$cooperative)->first();

$status=false;
$message='Could not verify farmer details.';
if($farmer){
$status=true;
$message='Farmer has been verified.';

//generate verification code
$code=FarmerVerificationService::generate_verification_code();
$segment=md5($code);
FarmerBatchVerification::create(['cooperative_id'=>$cooperative,'cooperative_farmers_id'=>$farmer->id,
'verification_code'=>$code,
'verification_id'=>$segment,
'expiry_minutes'=>10]);

return redirect()->route('cooperative.produce.create.batch', ['any' => $segment]);
}
return redirect()->back()->with('success',['status'=>$status,'message'=>$message]);

}















}
