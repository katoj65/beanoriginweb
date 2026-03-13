<?php

namespace App\Http\Controllers\Market;

use App\Http\Controllers\Controller;
use App\Http\Resources\BatchBidsResource;
use App\Models\Batch;
use App\Models\BatchBid;
use App\Services\Bid\BidService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Resources\BidMetadataResource;

class BiddingController extends Controller
{


public function index() : Response
{
// List only bidding batches for the bidding index page.
$batches = Batch::query()
->where('market_type','bidding')
->select([
'id',
'batch_code',
'commodity_name',
'commodity_type',
'grade',
'weight',
'quantity',
'price',
'warehouse',
'status',
'market_type',
])
->latest('id')
->limit(50)
->get();

$userId = (int) (auth()->id() ?? 0);

$batchIds = $batches
->pluck('id')
->map(fn ($id) => (int) $id)
->all();

// Query bid users for each batch in the bidding list.
$batchBidUsers = BatchBid::query()
->with('user:id,fname,lname,email')
->whereIn('batch_id', $batchIds)
->get(['batch_id', 'user_id', 'bid_price', 'status', 'created_at'])
->groupBy('batch_id');

$batches = $batches->map(function ($batch) use ($batchBidUsers, $userId) {
$bidRows = $batchBidUsers->get((int) $batch->id, collect());

$batch->has_bid_on_batch = $userId > 0
? $bidRows->contains(fn ($bid) => (int) $bid->user_id === $userId)
: false;

$batch->bid_users = $bidRows
->map(fn ($bid) => [
'id' => $bid->user?->id,
'fname' => $bid->user?->fname,
'lname' => $bid->user?->lname,
'email' => $bid->user?->email,
'bid_price' => (float) ($bid->bid_price ?? 0),
'status' => $bid->status,
'submitted_at' => $bid->created_at,
])
->values();

return $batch;
});

// Query bids where i am among bidders
$myBids = BatchBidsResource::collection(
BatchBid::query()
->with('batch:id,batch_code,commodity_name,commodity_type,grade,weight,quantity,price,warehouse')
->where('user_id', $userId)
->orderByDesc('id')
->limit(50)
->get()
)->resolve();


// Step 2: Fetch bids attached to owned batches without SQL joins.
$submittedBids =BidMetadataResource::collection(Batch::query()
->where('owner_id',$userId)
->where('market_type','bidding')
->orderByDesc('id')
->get());

return Inertia::render('BiddingPage', [
'batches' => $batches,
'my_bids' => $myBids,
'submitted_bids' => $submittedBids,
]);
}








public function show(Request $request, string $id, BidService $bidService) : Response
{
// Delegate batch bidding page payload building to BidService.
return $bidService->bidData($request, $id);
}









public function userBids(Request $request): Response
{
// Get bids submitted by the currently logged-in user.
$userId = (int) $request->user()->id;

$bids = BatchBid::query()
->with([
'batch:id,batch_code,commodity_name,commodity_type,grade,weight,quantity,price,warehouse',
])
->where('user_id', $userId)
->latest('id')
->get()
->map(fn (BatchBid $bid) => [
'id' => $bid->id,
'batch_id' => $bid->batch_id,
'batch_code' => $bid->batch?->batch_code,
'commodity_name' => $bid->batch?->commodity_name,
'commodity_type' => $bid->batch?->commodity_type,
'grade' => $bid->batch?->grade,
'weight' => $bid->batch?->weight,
'available_quantity' => $bid->batch?->quantity,
'ask_price' => $bid->batch?->price,
'warehouse' => $bid->batch?->warehouse,
'bid_quantity' => $bid->bid_quantity,
'bid_price' => $bid->bid_price,
'bid_notes' => $bid->bid_notes,
'status' => $bid->status,
'currency' => 'UGX',
'created_at' => $bid->created_at?->toDateTimeString(),
'request'=>$bid->user_id==$userId?true:false
])
->values();

// Render the user's bids listing page.
return Inertia::render('BidsUserPage', [
'bids' => $bids,
]);
}






public function storeBatchBidding(Request $request, string $id): RedirectResponse
{
// Validate incoming bid form values.
$payload = $request->validate([
'bid_quantity' => ['required', 'numeric', 'min:1'],
'bid_price' => ['required', 'numeric', 'min:0.01'],
'bid_notes' => ['nullable', 'string', 'max:1000'],
]);

// Load target batch and enforce bidding market type.
$batch = Batch::query()
->where('id', (int) $id)
->where('market_type', 'bidding')
->firstOrFail();

// Prevent users from bidding on their own batch.
$userId = (int) $request->user()->id;
if ((int) $batch->owner_id === $userId) {
return back()->withErrors([
'bid_price' => 'You cannot bid on your own batch.',
]);
}

// Enforce bid price to be at least the current ask price.
$inputBidPrice = (float) $payload['bid_price'];
$batchAskPrice = (float) ($batch->price ?? 0);
if ($inputBidPrice < $batchAskPrice) {
return back()->withErrors([
'bid_price' => 'Bid price cannot be lower than the batch ask price.',
]);
}

// Detect if this submit is a counter offer (user already has a bid on this batch).
$isCounterOffer = BatchBid::query()
->where('user_id', $userId)
->where('batch_id', $batch->id)
->exists();

// Store a single active bid per user per batch.
BatchBid::updateOrCreate(
['user_id' => $userId, 'batch_id' => $batch->id],
[
'bid_quantity' => (float) $payload['bid_quantity'],
'bid_price' => $inputBidPrice,
'bid_notes' => $payload['bid_notes'] ?? null,
'status' => 'pending',
]
);

if ($isCounterOffer) {
return redirect()
->route('bid.show', ['id' => $batch->id]);
}


// Redirect to "My Bids" after successful submission.
return redirect()->route('market.bids.user');
}
}
