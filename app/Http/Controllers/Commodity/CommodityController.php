<?php

namespace App\Http\Controllers\Commodity;

use App\Http\Controllers\Controller;
use App\Http\Resources\CooperativeFarmer as CooperativeFarmerResource;
use App\Http\Resources\CropGradeResource;
use App\Http\Resources\CropResource;
use App\Http\Resources\CropTypeResource;
use App\Http\Resources\FarmResource;
use App\Http\Resources\ProcessMethodResource;
use App\Models\Commodity;
use App\Models\Batch;
use App\Models\BatchStatusList;
use App\Models\Cooperative;
use App\Models\Farmer;
use App\Models\CropGrade;
use App\Models\Crops;
use App\Models\CropType;
use App\Models\Farm;
use App\Models\ProcessMethod;
use App\Services\Payments\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\CommodityResource;
use App\Http\Resources\BatchResource;
use App\Models\CommodityBatch;
use App\Models\BatchProcessingData;
use App\Models\CommodityQualityData;
use App\Models\QualityMetadata;
use App\Services\Blockchain\BlockService;
use Inertia\Inertia;

class CommodityController extends Controller
{
/**
 * Display a listing of the resource.
 */
public function index()
{
// Intentionally left blank. Listing is handled by dedicated pages/controllers.
}



/**
 * Store a newly created resource in storage.
 */
public function store(Request $request)
{
// Validate commodity harvest input from create form.
$validated = $request->validate([
'crop_name' => ['required', 'string', 'max:255'],
'crop_type' => ['required', 'string', 'max:255'],
'quantity' => ['required', 'numeric', 'min:0'],
'price' => ['required', 'numeric', 'min:0'],
'ripe_percentage' => ['nullable', 'integer', 'between:0,100'],
'density_percentage' => ['nullable', 'integer', 'between:0,100'],
'date_of_harvest' => ['required', 'date'],
'crop_grade' => ['required', 'string', 'max:255'],
'phone_number' => ['required', 'string', 'regex:/^[0-9+()\\-\\s]{7,20}$/'],
]);

// Resolve logged-in cooperative and locate farmer by submitted phone number.
$cooperativeId = Cooperative::where('user_id', $request->user()->id)->value('id');
$farmer = Farmer::query()
->where('cooperative_id', $cooperativeId)
->where('phone_number', $validated['phone_number'])
->first();

if (!$farmer) {
throw ValidationException::withMessages([
'phone_number' => 'No farmer found with the provided phone number in your cooperative.',
]);
}

// Create commodity row owned by this cooperative.
$commodity = Commodity::create([
'cooperative_id' => $cooperativeId,
'commodity_name' => $validated['crop_name'],
'commodity_type' => $validated['crop_type'],
'grade' => $validated['crop_grade'],
'weight' => $validated['quantity'],
'harvest_date' => $validated['date_of_harvest'],
'price' => $validated['price'],
'ripe_percentage' => $validated['ripe_percentage'] ?? null,
'density_percentage' => $validated['density_percentage'] ?? null,
]);

return redirect()
->route('commodity.origin-farms.create', ['id' => $commodity->id])
->with('success', 'Produce saved successfully.');
}







// Store a new batch record from commodity batch form.
public function storeBatch(Request $request, BlockService $blockService)
{
// Validate required batch fields and publish destination.
$validated = $request->validate([
'batch_code' => ['required', 'string', 'max:255', 'unique:batches,batch_code'],
'commodity_name' => ['required', 'string', 'max:255', 'exists:crops,name'],
'commodity_type' => ['required', 'string', 'max:255'],
'weight' => ['required', 'numeric', 'min:0.01'],
'quantity' => ['required', 'numeric', 'min:0.01'],
'price' => ['required', 'numeric', 'min:0.01'],
'market_type' => ['required', 'string', 'in:save,marketplace,bidding'],
'grade' => ['required', 'string', 'max:100', 'exists:crop_grades,name'],
'moisture' => ['nullable', 'numeric', 'min:0', 'max:100'],
'warehouse' => ['required', 'string', 'max:255'],

]);


// Persist batch atomically to keep data consistent.
$batch = DB::transaction(function () use ($validated, $request, $blockService) {
$batch = Batch::create([
'owner_id' => $request->user()->id,
'batch_code' => $validated['batch_code'],
'commodity_name' => $validated['commodity_name'],
'commodity_type' => $validated['commodity_type'],
'weight' => $validated['weight'],
'quantity' => $validated['quantity'] ?? 0,
'grade' => $validated['grade'],
'moisture' => $validated['moisture'] ?? null,
'warehouse' => $validated['warehouse'],
'is_on_chain' => false,
'status' => 'created',
'price' => $validated['price'],
'market_type' => $validated['market_type'] ?? 'save',
]);


// Add block only when batch is not saved as draft.
if (($validated['market_type'] ?? 'save') !== 'save') {
$blockService->addBlock($batch, [
'event_type' => 'created',
'action' => 'batch_created',
]);
}




return $batch;
});

return redirect()
->route('commodity.batch.verify', ['id' => $batch->id])
->with('success', 'Batch created successfully.');

}





/**
 * Display the specified resource.
 */
public function show(Request $request, string $id)
{

// Scope commodity visibility to the logged-in user's cooperative.
$cooperativeId = Cooperative::where('user_id', $request->user()->id)->value('id');
$commodity = Commodity::with('farms:id,cooperative_farmer_id,farm_name,location,area_acres')
->where('id', $id)
->where('cooperative_id', $cooperativeId)
->firstOrFail();

// Return commodity details, linked farms, and quality data for the page.
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

])
->values(),
// Provide selectable quality activity metadata for the quality form.
'quality_metadata' => QualityMetadata::query()
->orderBy('activity')
->pluck('activity')
->values(),
// Provide saved commodity quality rows for on-page quality table.
'commodity_quality_data' => CommodityQualityData::query()
->where('commodity_id', (int) $id)
->latest('id')
->get(['id', 'activity', 'value', 'created_at'])
->map(fn ($item) => [
'id' => $item->id,
'activity' => $item->activity,
'value' => $item->value,
'created_at' => $item->created_at?->toDateTimeString(),
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

// Render farm linking form with current linked farm ids.
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
// Intentionally left blank. Dedicated update endpoint is updateCommodityData().
}

/**
 * Remove the specified resource from storage.
 */
public function destroy(string $id)
{
// Intentionally left blank. Commodity deletion is not enabled from this controller.
}






public function create(Request $request)
{
// Load cooperative-scoped farms and supporting metadata for create form.
$cooperativeId = Cooperative::where('user_id', $request->user()->id)->value('id');
$farmerIds = Farmer::query()
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
private function addOriginFarmsToCommodity(Commodity $commodity, Farmer $farmer): void
{
// Attach all farms registered under the matched farmer.
$farmIds = Farm::query()
->where('cooperative_farmer_id', $farmer->id)
->pluck('id')
->all();
if (empty($farmIds)) {
throw ValidationException::withMessages([
'phone_number' => 'The matched farmer has no registered farm.',
]);
}
// Avoid duplicates if some farms were already linked.
$commodity->farms()->syncWithoutDetaching($farmIds);
}





// Submit selected farm ids for a commodity.
public function storeOriginFarms(Request $request, string $id)
{
// Validate selected farm ids.
$validated = $request->validate([
'farm_ids' => ['required', 'array', 'min:1'],
'farm_ids.*' => ['integer', 'distinct'],
]);

$cooperativeId = Cooperative::where('user_id', $request->user()->id)->value('id');
$commodity = Commodity::query()
->where('id', $id)
->where('cooperative_id', $cooperativeId)
->firstOrFail();

// Keep only farms that belong to this cooperative.
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

// Link farms, then mark commodity as pending review.
$commodity->farms()->syncWithoutDetaching($farmIds->all());
$model=$commodity;
$model->status='pending';
$model->save();

return redirect()
->route('commodity.show', ['id' => $commodity->id])
->with('success', 'Origin farms saved successfully.');

}







// Commodity farm details.
public function showOriginFarmDetails(Request $request, string $commodity, string $farm)
{
// Ensure commodity belongs to logged-in cooperative.
$cooperativeId = Cooperative::where('user_id', $request->user()->id)->value('id');

$commodityModel = Commodity::query()
->where('id', $commodity)
->where('cooperative_id', $cooperativeId)
->firstOrFail();

$farmModel = $commodityModel->farms()
->with('farmer')
->where('farms.id', $farm)
->firstOrFail();

// Render single farm details linked to this commodity.
return Inertia::render('CommodityFarm', [
'title' => 'Origin Farm Details',
'commodity' => new CommodityResource($commodityModel),
'farm' => new FarmResource($farmModel),
'owner' => $farmModel->farmer ? new CooperativeFarmerResource($farmModel->farmer) : null,
]);


}




public function editCommodityData(Request $request, string $id)
{
// Load commodity scoped to cooperative for edit page.
$cooperativeId = Cooperative::where('user_id', $request->user()->id)->value('id');
$commodity = Commodity::query()
->where('id', (int) $id)
->where('cooperative_id', $cooperativeId)
->firstOrFail();

$crops = Crops::query()->orderBy('name')->get();
$cropType = CropType::query()->orderBy('name')->get();
$grade = CropGrade::query()->orderBy('name')->get();

// Provide editable commodity payload plus select options.
return Inertia::render('CommodityUpdatePage', [
'title' => 'Update Commodity',
'commodity' => [
'id' => $commodity->id,
'commodity_name' => $commodity->commodity_name,
'commodity_type' => $commodity->commodity_type,
'grade' => $commodity->grade,
'weight' => $commodity->weight,
'price' => $commodity->price,
'ripe_percentage' => $commodity->ripe_percentage,
'density_percentage' => $commodity->density_percentage,
'harvest_date' => $commodity->harvest_date ? $commodity->harvest_date->format('Y-m-d') : null,
],
'crops' => CropResource::collection($crops),
'crop_type' => CropTypeResource::collection($cropType),
'crop_grade' => CropGradeResource::collection($grade),
]);
}


// Batch create form.
public function createBatch(Request $request)
{
// Load select options for batch creation.
$cooperativeId = Cooperative::where('user_id', $request->user()->id)->value('id');
$crops = Crops::query()->orderBy('name')->get(['id', 'name']);
$cropTypes = CropType::query()->orderBy('name')->get(['id', 'name']);
$grades = CropGrade::query()->orderBy('name')->get(['id', 'name']);
return Inertia::render('BatchCreate', [
'title' => 'Create Commodity Batch',
'crops' => $crops,
'crop_types' => $cropTypes,
'crop_type' => $cropTypes,
'grades' => $grades,
]);
}















// Batch commodity verification.
public function verifyBatchCommodities(Request $request, string $id)
{
// Fetch batch owned by current user.
$batch = Batch::query()
->where('id', $id)
->where('owner_id', $request->user()->id)
->firstOrFail();

$commodityIds = CommodityBatch::query()
->where('batch_id', $batch->id)
->pluck('commodity_id')
->filter()
->values();

// Build attached commodity and activity timelines for verification page.
$attachedCommodities = CommodityResource::collection(
Commodity::query()
->whereIn('id', $commodityIds)
->latest('id')
->get()
)->resolve();

$batchActivities = $batch->activities()
->latest('id')
->get(['id', 'activity', 'created_at'])
->map(fn ($activity) => [
'id' => $activity->id,
'activity' => $activity->activity,
'created_at' => $activity->created_at?->toDateTimeString(),
])
->values();

$batchProcessingMetadata = DB::table('processing_metadata')
->orderBy('name')
->pluck('name')
->values();

$batchProcessingData = BatchProcessingData::query()
->where('batch_id', $batch->id)
->latest('id')
->get(['id', 'activity', 'value', 'created_at'])
->map(fn ($item) => [
'id' => $item->id,
'activity' => $item->activity,
'value' => $item->value,
'created_at' => $item->created_at?->toDateTimeString(),
])
->values();

return Inertia::render('BatchCommodityVerification', [
'title' => 'Batch Commodity Verification',
'batch' => new BatchResource($batch),
'attached_commodities' => $attachedCommodities,
'batch_activities' => $batchActivities,
'batch_processing_metadata' => $batchProcessingMetadata,
'batch_processing_data' => $batchProcessingData,
'batch_status_list' => BatchStatusList::query()->where('name','!=','created')->orderBy('id')->pluck('name')->values(),
]);
}









public function attachCommodityToBatch(Request $request, string $id)
{
// Validate selected commodity id.
$validated = $request->validate([
'commodity_id' => ['required', 'integer', 'exists:commodities,id'],
]);

$batch = Batch::query()
->where('id', $id)
->where('owner_id', $request->user()->id)
->firstOrFail();

// Ensure selected commodity belongs to same cooperative.
$cooperativeId = Cooperative::where('user_id', $request->user()->id)->value('id');
$commodity = Commodity::query()
->where('id', $validated['commodity_id'])
->where('cooperative_id', $cooperativeId)
->first();

if (!$commodity) {
throw ValidationException::withMessages([
'commodity_id' => 'Commodity not found for your cooperative.',
]);
}

// Prevent duplicate commodity-to-batch links.
$alreadyAttached = CommodityBatch::query()
->where('batch_id', $batch->id)
->where('commodity_id', $commodity->id)
->exists();

if ($alreadyAttached) {
throw ValidationException::withMessages([
'commodity_id' => 'Commodity is already attached to this batch.',
]);
}

// Save commodity link to batch.
CommodityBatch::query()->create([
'batch_id' => $batch->id,
'commodity_id' => $commodity->id,
]);

return redirect()
->route('commodity.batch.verify', ['id' => $batch->id])
->with('success', 'Commodity attached to batch successfully.');
}







public function storeCommodityQualityData(Request $request, string $id){
// Validate quality activity/value submitted from CommodityShow page.
$validated = $request->validate([
'activity' => ['required', 'string', 'max:255'],
'value' => ['required', 'string', 'max:255'],
]);

// Ensure the commodity belongs to the logged-in cooperative user.
$cooperativeId = Cooperative::where('user_id', $request->user()->id)->value('id');
$commodity = Commodity::query()
->where('id', (int) $id)
->where('cooperative_id', $cooperativeId)
->firstOrFail();

// Block duplicate quality metric activity for this commodity.
$activity = trim((string) $validated['activity']);
$activityExists = CommodityQualityData::query()
->where('commodity_id', (int) $commodity->id)
->whereRaw('LOWER(activity) = ?', [strtolower($activity)])
->exists();

if ($activityExists) {
throw ValidationException::withMessages([
'activity' => 'This quality metric already exists for this commodity.',
]);
}

// Persist one quality data record for the selected commodity.
CommodityQualityData::create([
'commodity_id' => (int) $commodity->id,
'activity' => $activity,
'value' => $validated['value'],
]);

return redirect()
->route('commodity.show', ['id' => $commodity->id])
->with('success', 'Commodity quality data saved successfully.');
}



public function destroyCommodityQualityData(Request $request, string $id, string $qualityId){
// Ensure commodity belongs to current cooperative.
$cooperativeId = Cooperative::where('user_id', $request->user()->id)->value('id');
$commodity = Commodity::query()
->where('id', (int) $id)
->where('cooperative_id', $cooperativeId)
->firstOrFail();

// Delete only quality row that belongs to this commodity.
CommodityQualityData::query()
->where('id', (int) $qualityId)
->where('commodity_id', (int) $commodity->id)
->delete();

return redirect()
->route('commodity.show', ['id' => $commodity->id])
->with('success', 'Commodity quality data deleted successfully.');
}




public function updateCommodityData(Request $request, string $id){
// Validate editable commodity fields.
$validated = $request->validate([
'commodity_name' => ['required', 'string', 'max:255', 'exists:crops,name'],
'commodity_type' => ['required', 'string', 'max:255'],
'grade' => ['required', 'string', 'max:255', 'exists:crop_grades,name'],
'weight' => ['required', 'numeric', 'min:0'],
'price' => ['required', 'numeric', 'min:0'],
'ripe_percentage' => ['nullable', 'integer', 'between:0,100'],
'density_percentage' => ['nullable', 'integer', 'between:0,100'],
'harvest_date' => ['required', 'date'],
]);

// Scope update to commodity owned by logged-in cooperative.
$cooperativeId = Cooperative::where('user_id', $request->user()->id)->value('id');
$commodity = Commodity::query()
->where('id', (int) $id)
->where('cooperative_id', $cooperativeId)
->firstOrFail();

// Persist the updated commodity attributes.
$commodity->update([
'commodity_name' => $validated['commodity_name'],
'commodity_type' => $validated['commodity_type'],
'grade' => $validated['grade'],
'weight' => $validated['weight'],
'price' => $validated['price'],
'ripe_percentage' => $validated['ripe_percentage'] ?? null,
'density_percentage' => $validated['density_percentage'] ?? null,
'harvest_date' => $validated['harvest_date'],
]);

return redirect()
->route('commodity.show', ['id' => $commodity->id])
->with('success', 'Commodity updated successfully.');
}









}
