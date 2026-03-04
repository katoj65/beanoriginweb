<?php

namespace App\Http\Controllers\Market;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\BatchPurchaseRequest;
use App\Models\Commodity;
use App\Models\Cooperative;
use App\Models\ShoppingCart;
use App\Models\UserProfile;
use App\Services\Buy\BuyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class MarketController extends Controller
{
/**
 * Display a listing of the resource.
 */
public function index(): Response
{
$user = auth()->user();
$batches = Batch::query()
->latest('id')
->get()
->map(fn (Batch $batch) => $this->mapTokenizedBatch($batch))
->values();
return Inertia::render('TokenPage', [
'title' => 'Tokenized Batches',
'batches' => $batches,
]);
}





public function reservedMarket(): Response
{
$user = auth()->user();
$batches = Batch::query()
->where('status', 'reserved')
->latest('id')
->get()
->map(fn (Batch $batch) => $this->mapTokenizedBatch($batch))
->values();
return Inertia::render('TokenPage', [
'title' => 'Tokenized Batches',
'batches' => $batches,
]);
}





public function boughtMarket(): Response
{
$user = auth()->user();

$batches = Batch::query()
->where('status', 'bought')
->latest('id')
->get()
->map(fn (Batch $batch) => $this->mapTokenizedBatch($batch))
->values();
return Inertia::render('TokenPage', [
'title' => 'Tokenized Batches',
'batches' => $batches,
]);
}







public function marketRequests(Request $request): Response
{

$ownerId = (int) $request->user()->id;
$requests = DB::table('batches')
->join('batch_purchase_requests', 'batches.id', '=', 'batch_purchase_requests.batch_id')
->join('users', 'batch_purchase_requests.user_id', '=', 'users.id')
->join('user_profile','users.id','=','user_profile.user_id')
->where('batches.status', 'request')
->where('batches.owner_id',$ownerId)
->orWhere('batch_purchase_requests.user_id',$ownerId)
->orderByDesc('batch_purchase_requests.id')
->select([
'batch_purchase_requests.id',
'batch_purchase_requests.batch_id',
'batch_purchase_requests.activity',
'batch_purchase_requests.created_at',
'batches.batch_code',
'batches.commodity_name',
'batches.commodity_type',
'users.fname',
'users.lname',
'users.email as buyer_email',
'user_profile.tel',
'user_profile.address'
])
->get()
->map(function ($item) {
$buyerName = trim(($item->fname ?? '') . ' ' . ($item->lname ?? ''));

return [
'id' => $item->id,
'batch_id' => $item->batch_id,
'batch_code' => $item->batch_code,
'commodity_name' => $item->commodity_name,
'commodity_type' => $item->commodity_type,
'activity' => $item->activity,
'buyer_name' => $buyerName !== '' ? $buyerName : 'N/A',
'buyer_email' => $item->buyer_email ?? 'N/A',
'created_at' => $item->created_at,
'address'=>$item->address,
'telephone'=>$item->tel
];
})
->values();

return Inertia::render('MarketRequests', [
'title' => 'Market Requests',
'requests' => $requests,
]);
}










/**
 * Store a newly created resource in storage.
 */
public function store(Request $request)
{
//
}

/**
 * Store a batch purchase request using the same flow as BuyController store.
 */
public function storePurchaseRequest(Request $request, string $id, BuyService $buyService): RedirectResponse
{
// Normalize id when frontend accidentally submits an object payload.
$requestId = $request->input('id');
if (is_array($requestId) && isset($requestId['id'])) {
$request->merge(['id' => $requestId['id']]);
}
if (is_string($requestId) && str_starts_with(trim($requestId), '{')) {
$decoded = json_decode($requestId, true);
if (is_array($decoded) && isset($decoded['id'])) {
$request->merge(['id' => $decoded['id']]);
}
}

// Validate optional batch id submitted from the reserve form.
$validated = $request->validate([
'id' => ['nullable', 'integer', 'exists:batches,id'],
]);

// Resolve batch id from payload first, fallback to route parameter.
$batchId = (int) ($validated['id'] ?? $id);
// Load target batch for reservation.
$batch = Batch::query()->findOrFail($batchId);
// Delegate reservation logic to the buy service.
$buyService->ReserveBatch($batch, $request);
// Return to current page with feedback.
return back()->with('success', 'Batch selected for buy successfully.');
}

/**
 * Display the specified resource.
 */
public function show(Request $request, string $id): Response
{
// Resolve current batch and eager-load owner data for details page.
$batchId = (int) $request->segment(3, $id);
$batch = Batch::query()
->with('owner:id,fname,lname,email')
->findOrFail($batchId);

// Query owner user profile by batch owner id.
$ownerProfile = UserProfile::query()
->where('user_id', $batch->owner?->id)
->select(['user_id', 'tel', 'address'])
->first();

// Fallback contact source when user_profile is not yet created for this owner.
$ownerCooperative = Cooperative::query()
->where('user_id', $batch->owner?->id)
->select(['tel', 'physical_address'])
->first();

// Query commodities joined with farms via commodity_farms pivot, scoped to this batch.
$commodityFarmRows = Commodity::query()
->join('commodity_batches', 'commodities.id', '=', 'commodity_batches.commodity_id')
->leftJoin('commodity_farms', 'commodities.id', '=', 'commodity_farms.commodity_id')
->leftJoin('farms', 'commodity_farms.farm_id', '=', 'farms.id')
->where('commodity_batches.batch_id', $batch->id)
->select([
'commodities.id as commodity_id',
'commodities.commodity_name',
'commodities.commodity_type',
'commodities.grade',
'commodities.weight',
'commodities.harvest_date',
'commodities.status',
'farms.id as farm_id',
'farms.farm_name',
'farms.location',
'farms.area_acres',
'farms.primary_crop',
'farms.soil_type',
'farms.water_source_type',
])
->orderBy('commodities.commodity_name')
->orderBy('farms.farm_name')
->get();

// Build unique commodity list for table output.
$batchCommodities = $commodityFarmRows
->unique('commodity_id')
->map(fn ($row) => [
'id' => $row->commodity_id,
'commodity_name' => $row->commodity_name,
'commodity_type' => $row->commodity_type,
'grade' => $row->grade,
'weight' => $row->weight,
'harvest_date' => $row->harvest_date,
'status' => $row->status,
])
->values();

// Build flat farm rows linked to commodities in this batch.
$batchFarms = $commodityFarmRows
->filter(fn ($row) => !empty($row->farm_id))
->map(fn ($row) => [
'id' => $row->farm_id,
'commodity_id' => $row->commodity_id,
'commodity_name' => $row->commodity_name,
'farm_name' => $row->farm_name,
'location' => $row->location,
'area_acres' => $row->area_acres,
'primary_crop' => $row->primary_crop,
'soil_type' => $row->soil_type,
'water_source_type' => $row->water_source_type,
])
->unique(fn (array $farm) => $farm['id'].'-'.$farm['commodity_id'])
->values();

// Group each commodity with only its respective farms.
$commodityFarmMap = $commodityFarmRows
->groupBy('commodity_id')
->map(function ($rows) {
$first = $rows->first();
$farms = $rows
->filter(fn ($row) => !empty($row->farm_id))
->map(fn ($row) => [
'id' => $row->farm_id,
'farm_name' => $row->farm_name,
'location' => $row->location,
'area_acres' => $row->area_acres,
'primary_crop' => $row->primary_crop,
'soil_type' => $row->soil_type,
'water_source_type' => $row->water_source_type,
])
->unique('id')
->values();

return [
'id' => $first->commodity_id,
'commodity_name' => $first->commodity_name,
'commodity_type' => $first->commodity_type,
'farms' => $farms,
];
})
->values();



// Check whether logged-in user already reserved this batch.
$isReservedByUser = BatchPurchaseRequest::query()
->where('batch_id', $batchId)
->where('user_id', (int) $request->user()->id)
->where('activity', 'request')
->exists();

// Check if the logged-in user owns this batch.
$batchOwner = $batch->owner_id;
$userId = $request->user()->id;
$ownership = $batchOwner == $userId?true:false;

// Render buy page with batch details, reserve state, ownership, and linked commodities.
return Inertia::render('BuyBatchPage', [
'title' => 'Buy Batch',
'batch' => [
'id' => $batch->id,
'batch_code' => $batch->batch_code,
'commodity_name' => $batch->commodity_name,
'commodity_type' => $batch->commodity_type,
'grade' => $batch->grade,
'weight' => $batch->weight,
'price' => $batch->price,
'moisture' => $batch->moisture,
'warehouse' => $batch->warehouse,
'status' => $batch->status,
'created_at' => $batch->created_at?->toDateTimeString(),
'owner' => [
'id' => $batch->owner?->id,
'name' => trim(($batch->owner?->fname ?? '') . ' ' . ($batch->owner?->lname ?? '')),
'email' => $batch->owner?->email,
],
],
'is_reserved_by_user' => $isReservedByUser,
'batch_ownership'=> $ownership,
'batch_commodities' => $batchCommodities,
'batch_farms' => $batchFarms,
'commodity_farm_map' => $commodityFarmMap,
'owner_profile' => [
'tel' => $ownerProfile?->tel ?? $ownerCooperative?->tel,
'address' => $ownerProfile?->address ?? $ownerCooperative?->physical_address,
],
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







/**
 * Transform a tokenized batch model into page-friendly data.
 */
private function mapTokenizedBatch(Batch $batch): array
{
return [
'id' => $batch->id,
'batch_code' => $batch->batch_code,
'commodity_name' => $batch->commodity_name,
'commodity_type' => $batch->commodity_type,
'grade' => $batch->grade,
'weight' => $batch->weight,
'price' => $batch->price,
'warehouse' => $batch->warehouse,
'status' => $batch->status,
'created_at' => $batch->created_at?->toDateTimeString(),
];
}





public function purchaseRequest(Request $request, string $id, BuyService $buyService): RedirectResponse
{
// Normalize id when frontend accidentally submits an object payload.
$requestId = $request->input('id');
if (is_array($requestId) && isset($requestId['id'])) {
$request->merge(['id' => $requestId['id']]);
}
if (is_string($requestId) && str_starts_with(trim($requestId), '{')) {
$decoded = json_decode($requestId, true);
if (is_array($decoded) && isset($decoded['id'])) {
$request->merge(['id' => $decoded['id']]);
}
}

// Validate optional batch id submitted from the reserve form.
$validated = $request->validate([
'id' => ['nullable', 'integer', 'exists:batches,id'],
]);
// Resolve batch id from payload first, fallback to route parameter.
$batchId = (int) ($validated['id'] ?? $id);
// Load target batch for reservation.
$batch = Batch::query()->findOrFail($batchId);
// Delegate reservation logic to the buy service.
$buyService->ReserveBatch($batch, $request);
// Return to current page with feedback.
return back()->with('success', 'Batch selected for buy successfully.');
}






//add item to cart
public function storeNewCart(Request $request){
$validated = $request->validate([
'batch_id' => ['required', 'integer', 'exists:batches,id'],
]);

$userId = (int) $request->user()->id;
$batchId = (int) $validated['batch_id'];


$cartItem = ShoppingCart::query()->firstOrNew([
'user_id' => $userId,
'batch_id' => $batchId,
]);

if ($cartItem->exists) {
$cartItem->quantity = ((int) $cartItem->quantity) + 1;
} else {
$cartItem->quantity = 1;
$cartItem->status = 'active';
}
$cartItem->save();



return back()->with('success', 'Batch added to cart successfully.');
}







public function shoppingCart(Request $request): Response{
    $userId = (int) $request->user()->id;

    $cartItems = ShoppingCart::query()
    ->with('batch:id,batch_code,commodity_name,commodity_type,grade,weight,price,status,created_at')
    ->where('user_id', $userId)
    ->latest('id')
    ->get()
    ->map(function (ShoppingCart $item) {
        $unitPrice = (float) ($item->batch?->price ?? 0);
        $quantity = (int) ($item->quantity ?? 1);

        return [
        'id' => $item->id,
        'batch_id' => $item->batch_id,
        'batch_code' => $item->batch?->batch_code,
        'commodity_name' => $item->batch?->commodity_name,
        'commodity_type' => $item->batch?->commodity_type,
        'grade' => $item->batch?->grade,
        'weight' => $item->batch?->weight,
        'batch_status' => $item->batch?->status,
        'quantity' => $quantity,
        'unit_price' => $unitPrice,
        'line_total' => $unitPrice * $quantity,
        'created_at' => $item->created_at?->toDateTimeString(),
        ];
    })
    ->values();

    return Inertia::render('ShoppingCartPage', [
    'title' => 'Shopping Cart',
    'cart_items' => $cartItems,
    ]);
}






















































}
