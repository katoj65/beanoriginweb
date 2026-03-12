<?php

namespace App\Services\Bid;
use Illuminate\Http\Request;
use App\Models\Batch;
use App\Models\BatchBid;
use App\Models\Commodity;
use App\Models\Cooperative;
use App\Models\UserProfile;
use Inertia\Inertia;






class BidService{

public function bidData(Request $request, string $id){
// Resolve target bidding batch from route and validate market type.
// Resolve the bidding batch from route id and enforce bidding market type.
$batchId = (int) $request->segment(3, $id);
$batch = Batch::query()
->with('owner:id,fname,lname,email')
->where('id', $batchId)
->where('market_type', 'bidding')
->firstOrFail();

// Load owner profile contact details.
$ownerProfile = UserProfile::query()
->where('user_id', $batch->owner?->id)
->select(['user_id', 'tel', 'address'])
->first();

// Fallback to cooperative contact details if user profile is missing.
$ownerCooperative = Cooperative::query()
->where('user_id', $batch->owner?->id)
->select(['tel', 'physical_address'])
->first();

// Query batch commodities and linked farms in a single dataset.
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
])
->orderBy('commodities.commodity_name')
->orderBy('farms.farm_name')
->get();

// Build unique commodity records for the harvest details section.
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

// Build flat farm rows related to the batch commodities.
$batchFarms = $commodityFarmRows
->filter(fn ($row) => !empty($row->farm_id))
->map(fn ($row) => [
'id' => $row->farm_id,
'commodity_id' => $row->commodity_id,
'commodity_name' => $row->commodity_name,
'farm_name' => $row->farm_name,
'location' => $row->location,
'area_acres' => $row->area_acres,
])
->unique(fn (array $farm) => $farm['id'].'-'.$farm['commodity_id'])
->values();

// Group farms under each commodity for UI mapping.
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

// Ownership flags used by the page actions.
$isReservedByUser = false;
$batchOwner = $batch->owner_id;
$userId = $request->user()->id;
$ownership = $batchOwner == $userId?true:false;

// Check if the logged-in user already placed a bid on this batch.
$hasBidOnBatch = BatchBid::query()
->where('batch_id', $batch->id)
->where('user_id', $userId)
->exists();

// Load offers for this batch and split them into my offer and other buyers.
// Keep highest price first so UI rank #1 is always the top offer.
$batchBids = BatchBid::query()
->with('user:id,fname,lname,email')
->where('batch_id', $batch->id)
->orderByDesc('bid_price')
->orderByDesc('id')
->get();

$myBidRow = $batchBids->firstWhere('user_id', $userId);
$myBidOffer = $myBidRow ? [
'id' => $myBidRow->id,
'bid_quantity' => $myBidRow->bid_quantity,
'bid_price' => $myBidRow->bid_price,
'bid_notes' => $myBidRow->bid_notes,
'status' => $myBidRow->status,
'currency' => 'UGX',
'created_at' => $myBidRow->created_at?->toDateTimeString(),
] : null;

$otherBuyerOffers = $batchBids
->where('user_id', '!=', $userId)
->map(fn (BatchBid $bid) => [
'id' => $bid->id,
'buyer_name' => trim(($bid->user?->fname ?? '').' '.($bid->user?->lname ?? '')),
'bid_quantity' => $bid->bid_quantity,
'bid_price' => $bid->bid_price,
'status' => $bid->status,
'currency' => 'UGX',
'created_at' => $bid->created_at?->toDateTimeString(),
])
->values();

// Unified offers payload for the bidder table (includes my own offer marker).
$bidOffers = $batchBids
->map(fn (BatchBid $bid) => [
'id' => $bid->id,
'buyer_name' => trim(($bid->user?->fname ?? '').' '.($bid->user?->lname ?? '')),
'bid_quantity' => $bid->bid_quantity,
'bid_price' => $bid->bid_price,
'status' => $bid->status,
'currency' => 'UGX',
'created_at' => $bid->created_at?->toDateTimeString(),
'is_my_offer' => (int) $bid->user_id === $userId,
])
->values();
// Render batch bidding details page payload.
return Inertia::render('BatchBiddingPage',
 [
'title' => 'Bidding',
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
'has_bid_on_batch' => $hasBidOnBatch,
'my_bid_offer' => $myBidOffer,
'other_buyer_offers' => $otherBuyerOffers,
'bid_offers' => $bidOffers,
'batch_commodities' => $batchCommodities,
'batch_farms' => $batchFarms,
'commodity_farm_map' => $commodityFarmMap,
'owner_profile' => [
'tel' => $ownerProfile?->tel ?? $ownerCooperative?->tel,
'address' => $ownerProfile?->address ?? $ownerCooperative?->physical_address,
],
]);






}





































}














