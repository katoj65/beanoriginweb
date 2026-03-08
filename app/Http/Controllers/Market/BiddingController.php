<?php

namespace App\Http\Controllers\Market;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\BatchPurchaseRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BiddingController extends Controller
{
public function index(): Response
{
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
->get();

return Inertia::render('BiddingPage', [
'batches' => $batches,
]);
}

public function showBatchBidding(Request $request, string $id): Response
{
return app(MarketController::class)->showBatchBidding($request, $id);
}

public function storeBatchBidding(Request $request, string $id): RedirectResponse
{
$payload = $request->validate([
'bid_quantity' => ['required', 'numeric', 'min:1'],
'bid_price' => ['required', 'numeric', 'min:0.01'],
'bid_notes' => ['nullable', 'string', 'max:1000'],
]);

$batch = Batch::query()
->where('id', (int) $id)
->where('market_type', 'bidding')
->firstOrFail();

$userId = (int) $request->user()->id;
if ((int) $batch->owner_id === $userId) {
return back()->withErrors([
'bid_price' => 'You cannot bid on your own batch.',
]);
}

$activity = 'bidding_request|qty:'.$payload['bid_quantity'].'|price:'.$payload['bid_price'];
BatchPurchaseRequest::updateOrCreate(
['user_id' => $userId, 'batch_id' => $batch->id],
['activity' => $activity]
);

return back()->with('success', 'Bidding request submitted successfully.');
}
}
