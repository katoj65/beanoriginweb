<?php

namespace App\Http\Controllers\Produce;

use App\Http\Controllers\Controller;
use App\Models\Cooperative;
use App\Models\Farm;
use App\Models\Produce;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Crops;
use App\Http\Resources\CropResource;
use App\models\CropType;
use App\Http\Resources\CropTypeResource;
use App\Models\ProcessMethod;
use App\Http\Resources\ProcessMethodResource;
use App\Models\CropGrade;
use App\Http\Resources\CropGradeResource;
use App\Models\CooperativeFarmer;
use App\Services\FarmerVerificationService;
use App\Models\FarmerBatchVerification;
use App\Http\Resources\FarmersTableSummaryResource;



class ProduceController extends Controller
{
/**
 * Display a listing of the resource.
 */
public function index()
{
$cooperativeId = Cooperative::where('user_id', auth()->id())->value('id');
$farms = Farm::query()
->whereHas('farmer', function ($query) use ($cooperativeId) {
$query->where('cooperative_id', $cooperativeId);
})
->orderBy('farm_name')
->get(['id', 'farm_name']);


return Inertia::render('ProducePage', [
'title' => 'Add Produce',
'farms' => $farms,


]);
}

/**
 * Store a newly created resource in storage.
 */
public function store(Request $request)
{
$cooperativeId = Cooperative::where('user_id', auth()->id())->value('id');

if (! $cooperativeId) {
return back()->withErrors([
'cooperative_id' => 'No cooperative profile found for this account.',
])->withInput();
}

$validated = $request->validate([
'crop_name' => ['required', 'string', 'max:255'],
'crop_type' => ['required', 'string', 'max:255'],
'quantity' => ['required', 'numeric', 'min:0'],
'price' => ['required', 'numeric', 'min:0'],
'location' => ['required', 'string', 'max:255'],
'date_of_harvest' => ['nullable', 'date', 'required_without:date_of_havest'],
'date_of_harvest' => ['nullable', 'date', 'required_without:date_of_harvest'],
'crop_grade' => ['required', 'string', 'max:255'],
'process_method' => ['required', 'string', 'max:255'],
'status' => ['required', 'string', 'max:255'],


]);

Produce::create([
...$validated,
'cooperative_id' => $cooperativeId,
'date_of_harvest' => $validated['date_of_harvest'] ?? $validated['date_of_harvest'] ?? null,
]);

return redirect()->back()->with('success', 'Produce added successfully.');
}

/**
 * Display the specified resource.
 */
public function show(string $id)
{
//
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
$cooperativeId = Cooperative::where('user_id', auth()->id())->value('id');
$farms = Farm::query()
->whereHas('farmer', function ($query) use ($cooperativeId) {
$query->where('cooperative_id', $cooperativeId);
})
->orderBy('farm_name')
->get(['id', 'farm_name']);
$crops=Crops::get();
$crop_type=CropType::get();
$process_method=ProcessMethod::get();
$grade=CropGrade::get();

return Inertia::render('ProduceCreate', [
'title' => 'Add Produce',
'farms' => $farms,
'crops'=>CropResource::collection($crops),
'crop_type'=>CropTypeResource::collection($crop_type),
'process_method'=>ProcessMethodResource::collection($process_method),
'crop_grade'=>CropGradeResource::collection($grade)


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
$farms = Farm::query()
->whereHas('farmer', function ($query) use ($cooperativeId) {
$query->where('cooperative_id', $cooperativeId);
})
->orderBy('farm_name')
->get(['id', 'farm_name']);
$crops=Crops::get();
$crop_type=CropType::get();
$process_method=ProcessMethod::get();
$grade=CropGrade::get();
$farmer_id=$verification->cooperative_farmers_id;
$farmer=CooperativeFarmer::where('id',$farmer_id)->first();




return Inertia::render('ProduceCreateAfterVerification', [
'title' => 'Add Produce',
'farms' => $farms,
'crops'=>CropResource::collection($crops),
'crop_type'=>CropTypeResource::collection($crop_type),
'process_method'=>ProcessMethodResource::collection($process_method),
'crop_grade'=>CropGradeResource::collection($grade),
'farmer'=> new FarmersTableSummaryResource($farmer)





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
