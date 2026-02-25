<?php

namespace App\Http\Controllers\Commodity;

use App\Http\Controllers\Controller;
use App\Http\Resources\CropGradeResource;
use App\Http\Resources\CropResource;
use App\Http\Resources\CropTypeResource;
use App\Http\Resources\ProcessMethodResource;
use App\Models\Commodity;
use App\Models\Cooperative;
use App\Models\CooperativeFarmer;
use App\Models\CropGrade;
use App\Models\Crops;
use App\Models\CropType;
use App\Models\Farm;
use App\Models\ProcessMethod;
use App\Services\Payments\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\CommodityResource;
use Inertia\Inertia;

class CommodityController extends Controller
{
/**
 * Display a listing of the resource.
 */
public function index()
{
//
}

/**
 * Store a newly created resource in storage.
 */
public function store(Request $request)
{
$validated = $request->validate([
'crop_name' => ['required', 'string', 'max:255'],
'crop_type' => ['required', 'string', 'max:255'],
'quantity' => ['required', 'numeric', 'min:0'],
'price' => ['required', 'numeric', 'min:0'],
'date_of_harvest' => ['required', 'date'],
'crop_grade' => ['required', 'string', 'max:255'],
'phone_number' => ['required', 'string', 'regex:/^[0-9+()\\-\\s]{7,20}$/'],
]);

$cooperativeId = Cooperative::where('user_id', $request->user()->id)->value('id');
$farmer = CooperativeFarmer::query()
->where('cooperative_id', $cooperativeId)
->where('phone_number', $validated['phone_number'])
->first();

if (!$farmer) {
throw ValidationException::withMessages([
'phone_number' => 'No farmer found with the provided phone number in your cooperative.',
]);
}

$commodity = Commodity::create([
'cooperative_id' => $cooperativeId,
'commodity_name' => $validated['crop_name'],
'commodity_type' => $validated['crop_type'],
'grade' => $validated['crop_grade'],
'weight' => $validated['quantity'],
'harvest_date' => $validated['date_of_harvest'],
'price' => $validated['price'],
]);

return redirect()
->route('commodity.origin-farms.create', ['id' => $commodity->id])
->with('success', 'Produce saved successfully.');
}








/**
 * Display the specified resource.
 */
public function show(Request $request, string $id)
{

$cooperativeId = Cooperative::where('user_id', $request->user()->id)->value('id');
$commodity = Commodity::with('farms:id,cooperative_farmer_id,farm_name,location,area_acres,primary_crop,soil_type,water_source_type')
->where('id', $id)
->where('cooperative_id', $cooperativeId)
->firstOrFail();

// $farms=DB::table('commodity_farms')
// ->join('farms', 'commodity_farms.farm_id', '=', 'farms.id')
// ->where('commodity_farms.commodity_id', $id)
// ->select('farms.id',
// 'farms.farm_name',
// 'location',
// 'area_acres',
// 'soil_type',
// 'water_source_type')
// ->get();

return Inertia::render('CommodityShow', [
'commodity' => new CommodityResource($commodity),
'origin_farms' => $commodity->farms
->map(fn ($farm) => [
'id' => $farm->id,
'cooperative_farmer_id' => $farm->cooperative_farmer_id,
'farm_name' => $farm->farm_name,
'location' => $farm->location,
'latitude' => $farm->latitude,
'longitude' => $farm->longitude,
'area_acres' => $farm->area_acres,
'primary_crop' => $farm->primary_crop,
'soil_type' => $farm->soil_type,
'water_source_type' => $farm->water_source_type,
'farmer_last_name' => $farm->farmer_last_name,
'farmer_telephone' => $farm->farmer_telephone,

])
->values(),
]);
}







public function addOriginFarms(Request $request, string $id)
{
// This method will display the form to add origin farms to a commodity.
$cooperativeId = Cooperative::where('user_id', $request->user()->id)->value('id');
$commodity = Commodity::with('farms')
->where('id', $id)
->where('cooperative_id', $cooperativeId)
->where('status', 'created') // Only allow adding farms if commodity is still pending
->firstOrFail();

$farms = Farm::query()
->select('id', 'farm_name')
->whereHas('farmer', function ($query) use ($cooperativeId) {
$query->where('cooperative_id', $cooperativeId);
})
->orderBy('farm_name')
->get();

return Inertia::render('CommodityAddFarms', [
'title'=>'Add Origin Farms',
'commodity' => new CommodityResource($commodity),
'farms' => $farms,
'origin_farm_ids' => $commodity->farms->pluck('id')->values(),
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
$cooperativeId = Cooperative::where('user_id', $request->user()->id)->value('id');
$farmerIds = CooperativeFarmer::query()
->where('cooperative_id', $cooperativeId)
->pluck('id');
$farms = Farm::query()
->select('id', 'farm_name')
->whereIn('cooperative_farmer_id', $farmerIds)
->get();

$crops = Crops::get();
$cropType = CropType::get();
$processMethod = ProcessMethod::get();
$grade = CropGrade::get();

return Inertia::render('CommodityCreate', [
'farms' => $farms,
'crops' => CropResource::collection($crops),
'crop_type' => CropTypeResource::collection($cropType),
'process_method' => ProcessMethodResource::collection($processMethod),
'crop_grade' => CropGradeResource::collection($grade),
]);
}








/**
 * Link the newly posted commodity to farms where it originated.
 */
private function addOriginFarmsToCommodity(Commodity $commodity, CooperativeFarmer $farmer): void
{
$farmIds = Farm::query()
->where('cooperative_farmer_id', $farmer->id)
->pluck('id')
->all();
if (empty($farmIds)) {
throw ValidationException::withMessages([
'phone_number' => 'The matched farmer has no registered farm.',
]);
}
$commodity->farms()->syncWithoutDetaching($farmIds);
}





// submit farm ids for a commodity
public function storeOriginFarms(Request $request, string $id)
{
$validated = $request->validate([
'farm_ids' => ['required', 'array', 'min:1'],
'farm_ids.*' => ['integer', 'distinct'],
]);

$cooperativeId = Cooperative::where('user_id', $request->user()->id)->value('id');
$commodity = Commodity::query()
->where('id', $id)
->where('cooperative_id', $cooperativeId)
->firstOrFail();

$farmIds = Farm::query()
->whereIn('id', $validated['farm_ids'])
->whereHas('farmer', function ($query) use ($cooperativeId) {
$query->where('cooperative_id', $cooperativeId);
})
->pluck('id')
->values();

if ($farmIds->isEmpty()) {
throw ValidationException::withMessages([
'farm_ids' => 'Select at least one valid farm for your cooperative.',
]);
}

$commodity->farms()->syncWithoutDetaching($farmIds->all());
$model=$commodity;
$model->status='pending';
$model->save();

return redirect()
->route('commodity.show', ['id' => $commodity->id])
->with('success', 'Origin farms saved successfully.');



}
























}
