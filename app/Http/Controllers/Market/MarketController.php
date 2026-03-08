<?php

namespace App\Http\Controllers\Market;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\BatchPurchaseRequest;
use App\Models\Commodity;
use App\Models\Cooperative;
use App\Models\Payment;
use App\Models\Purchase;
use App\Models\ShoppingCart;
use App\Models\User;
use App\Models\UserProfile;
use App\Services\Buy\BuyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
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
->where('market_type','marketplace')
->latest('id')
->get()
->map(fn (Batch $batch) => $this->mapTokenizedBatch($batch))
->values();
return Inertia::render('TokenPage', [
'title' => 'Tokenized Batches',
'batches' => $batches,
]);
}





/**
 * Display batches currently marked as reserved.
 */
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





/**
 * Display batches currently marked as bought.
 */
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
'quantity' => $batch->quantity,
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
'quantity' => $batch->quantity,
'price' => $batch->price,
'warehouse' => $batch->warehouse,
'status' => $batch->status,
'created_at' => $batch->created_at?->toDateTimeString(),
];
}





/**
 * Store a purchase request entry for a batch.
 */
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






/**
 * Add a batch to the logged-in user's active cart.
 */
public function storeNewCart(Request $request){
// Validate required batch id and requested quantity.
$validated = $request->validate([
'batch_id' => ['required', 'integer', 'exists:batches,id'],
'quantity' => ['required', 'integer', 'min:1'],
]);

// Resolve request values and load the tokenized batch stock.
$userId = (int) $request->user()->id;
$batchId = (int) $validated['batch_id'];
$quantity = (int) $validated['quantity'];
$batch = Batch::query()->select(['id', 'quantity'])->where('status','tokenised')->findOrFail($batchId);
$availableQuantity = (float) ($batch->quantity ?? 0);

// Prevent adding more than the currently available batch quantity.
if ($quantity > $availableQuantity) {
throw ValidationException::withMessages([
'quantity' => 'Requested quantity cannot be greater than the available batch quantity.',
]);
}

// Reuse existing cart line for this user + batch, otherwise create one.
$cartItem = ShoppingCart::query()->firstOrNew([
'user_id' => $userId,
'batch_id' => $batchId,
]);

// Increase existing quantity, or initialize a new active cart row.
if ($cartItem->exists) {

$batchQty = (int) $batch->quantity;
$cartQty = (int) $cartItem->quantity;
$cartQtySum = $cartQty + $quantity;

if($quantity==0){
throw ValidationException::withMessages([
'quantity' => 'Quantity cannot be 0'
]);
}

if($cartQtySum>$batchQty){
throw ValidationException::withMessages([
'quantity' => 'You have '.$cartQty.'/'.$batchQty. ' of this batch in your cart.',
]);

}

$cartItem->quantity = ((int) $cartItem->quantity) + $quantity;

} else {
$cartItem->quantity = $quantity;
$cartItem->status = 'active';
}

$cartItem->save();
return back()->with('success', 'Batch added to cart successfully.');
}




/**
 * Render the shopping cart page for the logged-in user.
 */
public function shoppingCart(Request $request): Response{
    $userId = (int) $request->user()->id;

    $cartItems = $this->mapCartItems($userId);

    return Inertia::render('ShoppingCartPage', [
    'title' => 'Shopping Cart',
    'cart_items' => $cartItems,
    ]);
}




/**
 * Render checkout page with active cart items and totals.
 */
public function checkout(Request $request): Response{
    $userId = (int) $request->user()->id;
    $cartItems = $this->mapCartItems($userId);
    $cartTotal = $cartItems->sum('line_total');

    return Inertia::render('CheckoutPage', [
    'title' => 'Checkout',
    'cart_items' => $cartItems,
    'cart_total' => $cartTotal,
    ]);
}


/**
 * Map active shopping cart rows into the payload used by Inertia pages.
 */
private function mapCartItems(int $userId)
{
    return ShoppingCart::query()
    ->with('batch:id,batch_code,commodity_name,commodity_type,grade,weight,price,status,created_at')
    ->where('user_id', $userId)
    ->where('status', 'active')
    ->latest('id')
    ->get()
    // Flatten cart row + related batch data into a single display object.
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
}








/**
 * Validate checkout input and ensure the user still has active cart items.
 */
public function storeCheckout(Request $request) : RedirectResponse
{
$validated=$request->validate([
'shipping_name' => ['required', 'string', 'max:255'],
'shipping_phone' => ['required', 'string', 'max:50'],
'shipping_email' => ['required', 'email', 'max:255'],
'shipping_country' => ['required', 'string', 'max:120'],
'shipping_city' => ['required', 'string', 'max:120'],
'shipping_address' => ['required', 'string', 'max:1000'],
'shipping_notes' => ['nullable', 'string', 'max:1000'],
'payment_method' => ['required', 'string', 'in:mobile_money,bank_transfer,card'],
'payer_name' => ['required', 'string', 'max:255'],
'payment_reference' => ['required', 'string', 'max:255'],
]);




$userId = (int) $request->user()->id;
//get cart data
$cart = ShoppingCart::query()->where(['user_id'=>$userId,'status'=>'active'])->get();
//if the cart is empty redirect back to the cart page
if(count($cart)==0){
return back()->with('error', 'No active cart items found for checkout.');
}

//group transaction
DB::transaction(function() use($cart, $userId, $validated){
//crate a checkout code

$checkoutCode = hash('sha256',$userId.'-'.now()->format('YmdHis'));

// Aggregate session-level payment totals across all cart rows.
$numberOfItems = (int) $cart->count();
$totalQuantity = 0;
$totalAmount = 0;

foreach($cart as $item){
// Resolve seller and unit price from the source batch.
$ownerId = (int) (Batch::query()->where('id', $item->batch_id)->value('owner_id') ?? 0);
$unitPrice = (float) (Batch::query()->where('id', $item->batch_id)->value('price') ?? 0);
$lineTotal = ((float) ($item['quantity'] ?? 0)) * $unitPrice;

// Step 1: reduce this batch stock before recording purchase/payment.
$reduced = self::reduceBatchQuantity((int) $item->batch_id, (float) ($item['quantity'] ?? 0));

// Step 2: stop and rollback if batch stock is not enough.
if (! $reduced) {
throw ValidationException::withMessages([
'quantity' => 'Batch quantity is not enough to complete this purchase.',
]);
}

// Step 3: update running checkout totals for the Payment record.
$totalQuantity += (float) ($item['quantity'] ?? 0);
$totalAmount += $lineTotal;
// Step 4: store purchase after successful stock deduction.
Purchase::create([
'batch_id' => $item->batch_id,
'buyer_id' => $userId,
'shopping_cart_session'=>$checkoutCode,
'seller_id' => $ownerId,
'quantity' => (float) ($item['quantity'] ?? 0),
'unit_price' => $unitPrice,
	'total_price' => $lineTotal,
	'currency' => 'UGX',
	'payment_method' =>$validated['payment_method'],
	'transaction_reference' =>$validated['payment_reference'],
	'status' => 'completed',
	'notes' =>$validated['shipping_notes'] ?: null,
	'address' => $validated['shipping_address'],
	]);


}

// Store one Payment row for the whole shopping cart session.
Payment::create([
'user_id' => $userId,
'shopping_cart_session' => $checkoutCode,
'transaction_reference' => $validated['payment_reference'],
'number_of_items' => $numberOfItems,
'quantity' => (float) $totalQuantity,
'currency' => 'UGX',
'total_amount' => (float) $totalAmount,
'status' => 'complete',
]);

// Clear all active cart rows for this user after successful checkout.
ShoppingCart::query()->where(['user_id' => $userId, 'status' => 'active'])->delete();


});


return redirect()
->route('market.purchaseConfirmation')
->with([
'success' => 'Checkout details submitted successfully.']);

}







//get batch by batch ID
public function getBatchDetails($id)
{
return Batch::where('id',$id)->first()->id;
}







/**
 * Delete one active cart item owned by the logged-in user.
 */
public function destroyCartItem(Request $request, string $id) : RedirectResponse
{
    ShoppingCart::query()
    ->where('id', (int) $id)
    ->where('user_id', (int) $request->user()->id)
    ->where('status', 'active')
    ->delete();

    return back()->with('success', 'Cart item removed successfully.');
}


/**
 * Render purchase confirmation details after checkout submission.
 * Falls back to recalculated cart totals when flash session data is missing.
 */



public function purchaseConfirmation(Request $request) : Response
{
$user = $request->user();
$userId = (int) $user->id;
$buyer = User::query()->select(['id', 'fname', 'lname', 'email'])->find($userId);
$payment = Payment::where('user_id',$userId)->latest('id')->first();
$cartSession = (string) ($payment?->shopping_cart_session ?? '');
$purchasesQuery = Purchase::with('buyer:id,fname,lname,email')
->where('buyer_id', $userId);
if ($cartSession !== '') {
$purchasesQuery->where('shopping_cart_session', $cartSession);
}
$purchases = $purchasesQuery->latest('id')->get();
if ($purchases->isEmpty()) {
$purchases = Purchase::with('buyer:id,fname,lname,email')
->where('buyer_id', $userId)
->latest('id')
->get();
if ($cartSession === '') {
$cartSession = (string) ($purchases->first()?->shopping_cart_session ?? '');
}
}

$profile = UserProfile::query()
->where('user_id', $userId)
->first(['tel', 'address']);
$shippingInfo = [
'name' => trim(($buyer?->fname ?? '').' '.($buyer?->lname ?? '')) ?: null,
'email' => $buyer?->email ?? null,
'phone' => $profile?->tel,
'address' => $purchases->first()?->address ?: $profile?->address,
'notes' => $purchases->first()?->notes,
];

return Inertia::render('PurchaseConfirmationPage', [
'title' => 'Purchase Confirmation',
'purchases' => $purchases,
'shipping_info' => $shippingInfo,
'buyer' => $buyer,

]);
}







//Function to reduce the quantity of the item
static function reduceBatchQuantity($id, $count) : bool
{
// Step 1: find the batch row by id.
$batch = Batch::where('id',$id)->first();
if (! $batch) {
return false;
}
// Step 2: subtract bought quantity from current quantity.
$qtty = (float) $batch->quantity;
$new = (float) $qtty - (float) $count;
if($new < 0){
return false;
}
// Step 3: save the new quantity.
$batch->quantity=$new;
$batch->save();
return true;
}





























}
