<?php

namespace App\Services\Batch;
use App\Models\Commodity;
use App\Models\Batch;
use App\Models\BatchStatusList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\BatchAttachedCommodityResource;
use App\Http\Resources\BatchResource;
use App\Models\CommodityBatch;
use App\Models\BatchProcessingData;
use App\Models\Token;
use App\Http\Resources\TokenResource;
use Inertia\Inertia;




class BatchService {

// Build all payload data needed for BatchCommodityVerification page.
public function batch(Request $request, string $id)
{
// Fetch batch owned by current user.
$batch = Batch::query()
->where('id', $id)
->firstOrFail();

// Get commodity IDs currently linked to this batch.
$commodityIds = CommodityBatch::query()
->where('batch_id', $batch->id)
->pluck('commodity_id')
->filter()
->values();

// Build attached commodity and activity timelines for verification page.
$attachedCommodities = BatchAttachedCommodityResource::collection(
Commodity::query()
->whereIn('id', $commodityIds)
->latest('id')
->get()
)->resolve();

// Load all batch activity entries in latest-first order.
$batchActivities = $batch->activities()
->latest('id')
->get(['id', 'activity', 'created_at'])
->map(fn ($activity) => [
'id' => $activity->id,
'activity' => $activity->activity,
'created_at' => $activity->created_at?->toDateTimeString(),
])
->values();

// Load dropdown options for batch processing activity selection.
$batchProcessingMetadata = DB::table('processing_metadata')
->orderBy('name')
->pluck('name')
->values();

// Load existing processing rows displayed in the batch processing table.
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

$batchToken= Token::query()
->where('batch_id', $batch->id)
->latest('id')
->get(['id', 'token_index', 'event_type', 'current_hash', 'metadata', 'status', 'created_at']) ;





// Return full page payload for tabs: commodities, processing, and activities.
return Inertia::render('BatchPage', [
'title' => 'Batch Commodity Verification',
'batch' => new BatchResource($batch),
'attached_commodities' => $attachedCommodities,
'batch_activities' => $batchActivities,
'batch_processing_metadata' => $batchProcessingMetadata,
'batch_processing_data' => $batchProcessingData,
'batch_tokens' => TokenResource::collection($batchToken),
'batch_status_list' => BatchStatusList::query()->where('name','!=','created')->orderBy('id')->pluck('name')->values(),
'can'=>[
'view' => $request->user()?->can('view', $batch) ?? false,
'update' => $request->user()?->can('update', $batch) ?? false,
'delete' => $request->user()?->can('delete', $batch) ?? false,
'create' => $request->user()?->can('create', Batch::class) ?? false,
'view_any' => $request->user()?->can('viewAny', Batch::class) ?? false,
'owns_batch' => $request->user()?->can('ownsBatch', $batch) ?? false,
]

]);
}




static function ownershipCheck($ownerID,$userId):bool
{
if($ownerID==$userId){
return true;
}
return false;
}














}
