<?php

namespace App\Http\Controllers\Batch;

use App\Http\Controllers\Controller;
use App\Http\Resources\BatchResource;
use App\Models\Batch;
use App\Models\BatchActivity;
use App\Models\BatchTradeActivityData;
use App\Models\CommodityBatch;
use App\Models\BatchProcessingData;
use App\Models\CropGrade;
use App\Models\CropType;
use App\Models\Crops;
use App\Models\UserProfile;
use App\Services\Batch\BatchService;
use App\Services\Blockchain\BatchChainService;
use App\Services\Blockchain\BlockService;
use App\Services\Token\TokenService;
use App\Models\Block;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Inertia\Inertia;


class BatchController extends Controller
{
/**
 * Display the create form.
 */
public function create(Request $request)
{
// Load reference data needed to render the batch creation form.
$crops = Crops::query()
->orderBy('name')
->get(['id', 'name']);

$cropTypes = CropType::query()
->orderBy('name')
->get(['id', 'name']);

$grades = CropGrade::query()
->orderBy('name')
->get(['id', 'name']);


return Inertia::render('BatchCreate', [
'crops' => $crops,
'crop_type' => $cropTypes,
'grades' => $grades,
'status_options' => ['created', 'processing', 'processed', 'hulled', 'graded', 'listed', 'sold'],
]);


}






/**
 * Store a newly created resource in storage.
 */
public function store(Request $request, BatchChainService $batchChainService, BlockService $blockService)
{
// Validate input and create a new batch record for the authenticated user.
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


// Use a transaction to ensure atomicity of batch creation and blockchain recording.
$batch = DB::transaction(function () use ($request, $validated, $batchChainService, $blockService) {
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

return $batch;
});


return redirect()
->route('cooperative.batches.show', ['id' => $batch->id])
->with('success', 'Batch created successfully.');

}






/**
 * Display the specified resource.
 */
public function show(string $id)
{
// Load one batch with owner and latest matching on-chain block details.
$batch = Batch::query()
->with('owner:id,fname,lname,email')
->findOrFail($id);

$blockchain = Block::where('batch_id', $batch->id)
->where('current_owner', $batch->owner_id)
->where('previous_owner', $batch->owner_id)
->latest('block_index')
->first();

// Query profile using the user who owns this batch.
$ownerProfile = UserProfile::query()
->where('user_id', $batch->owner_id)
->first(['id', 'user_id', 'tel', 'address']);

if ($batch->owner) {
$batch->owner->setRelation('userProfile', $ownerProfile);
}

return Inertia::render('BatchDetailsPage', [
'batch' => new BatchResource($batch),
'block'=>$blockchain,
'block_price' =>'',



]);
}








public function storeBatchActivity(Request $request, string $id)
{
// Attach one verification workflow activity to the selected batch.
$validated = $request->validate([
'activity' => ['required', 'string', 'exists:batch_status_list,name'],
]);

$batch = Batch::query()
->where('id', $id)
->where('owner_id', auth()->id())
->firstOrFail();

$alreadyCompleted = BatchActivity::query()
->where('batch_id', $batch->id)
->where('activity', $validated['activity'])
->exists();

if ($alreadyCompleted) {
throw ValidationException::withMessages([
'activity' => 'This activity is already completed for this batch.',
]);
}

BatchActivity::create([
'batch_id' => $batch->id,
'activity' => $validated['activity'],
]);

return redirect()
->route('commodity.batch.verify', ['id' => $batch->id])
->with('success', 'Batch activity added successfully.');
}





/**
 * Update an existing batch for the authenticated owner.
 */
public function update(Request $request, string $id)
{
// Restrict updates to batches owned by the current user.
$batch = Batch::query()
->where('id', $id)
->where('owner_id', $request->user()->id)
->firstOrFail();

// Keep validation rules aligned with batch creation, while allowing current batch code.
$validated = $request->validate([
'batch_code' => ['required', 'string', 'max:255', Rule::unique('batches', 'batch_code')->ignore($batch->id)],
'commodity_name' => ['required', 'string', 'max:255', 'exists:crops,name'],
'commodity_type' => ['required', 'string', 'max:255'],
'weight' => ['required', 'numeric', 'min:0.01'],
'quantity' => ['nullable', 'numeric', 'min:0'],
'price' => ['required', 'numeric', 'min:0.01'],
'grade' => ['required', 'string', 'max:100', 'exists:crop_grades,name'],
'moisture' => ['nullable', 'numeric', 'min:0', 'max:100'],
'warehouse' => ['required', 'string', 'max:255'],
]);

// Normalize payload once so we can both compare and update from the same values.
$payload = [
'batch_code' => $validated['batch_code'],
'commodity_name' => $validated['commodity_name'],
'commodity_type' => $validated['commodity_type'],
'weight' => $validated['weight'],
'quantity' => $validated['quantity'] ?? ($batch->quantity ?? 0),
'price' => $validated['price'],
'grade' => $validated['grade'],
'moisture' => $validated['moisture'] ?? null,
'warehouse' => $validated['warehouse'],
];

// Prevent no-op updates and return a form-level validation error.
$numericFields = ['weight', 'quantity', 'price', 'moisture'];
$hasChanges = false;
foreach ($payload as $field => $value) {
$current = $batch->{$field};

if (in_array($field, $numericFields, true)) {
$currentValue = $current === null ? null : (float) $current;
$newValue = $value === null || $value === '' ? null : (float) $value;
if ($currentValue !== $newValue) {
$hasChanges = true;
break;
}
continue;
}

if ((string) $current !== (string) $value) {
$hasChanges = true;
break;
}
}

if (!$hasChanges) {
throw ValidationException::withMessages([
'batch' => 'Nothing has been changed.',
]);
}

$batch->update($payload);

return redirect()
->route('commodity.batch.verify', ['id' => $batch->id])
->with('success', 'Batch updated successfully.');
}





/**
 * Delete a batch owned by the authenticated user.
 */
public function destroy(Request $request, string $id)
{
// Delete a batch only when the current user is its owner.
$batch = Batch::query()
->where('id', $id)
->where('owner_id', $request->user()->id)
->firstOrFail();

$batch->delete();

return redirect()
->route('cooperative.produce')
->with('success', 'Batch deleted successfully.');
}



/**
 * Render the edit page for a batch owned by the authenticated user.
 */
public function edit(Request $request, string $id)
{
// Scope the edit target to the logged-in cooperative user.
$batch = Batch::query()
->where('id', $id)
->where('owner_id', $request->user()->id)
->firstOrFail();

// Load dropdown options used by the edit form.
$crops = Crops::query()
->orderBy('name')
->get(['id', 'name']);

$grades = CropGrade::query()
->orderBy('name')
->get(['id', 'name']);

return Inertia::render('BatchEdit', [
'batch' => new BatchResource($batch),
'crops' => $crops,
'grades' => $grades,
]);
}





// Custom method to handle batch verification action form submission.
public function listOnChain(Request $request, string $id, BlockService $blockService)
{
// List the batch on-chain and persist the corresponding blockchain event.
$validated = $request->validate([
'price' => ['required', 'numeric', 'min:0.01'],
]);

$batch = Batch::query()
->where('id', $id)
->where('owner_id', auth()->id())
->firstOrFail();

if ((bool) $batch->is_on_chain) {
return back()->withErrors([
'price' => 'Batch is already listed on chain.',
])->withInput();
}

DB::transaction(function () use ($batch, $validated, $blockService) {
$batch->update([
'price' => $validated['price'],
'is_on_chain' => true,
'status' => 'listed',
]);

$block = $blockService->addBlock($batch, [
'event_type' => 'listed',
'action' => 'listed_on_chain',
'entered_price' => $validated['price'],
]);


});

return redirect()
->route('cooperative.batches.show', ['id' => $batch->id])
->with('success', 'Batch listed on chain successfully.');

}






public function batchSaved(Request $request){
// Build the saved-batches table for the authenticated cooperative user.
$user = $request->user();

$batches = Batch::query()
->where('owner_id', $user->id)
->where('market_type', 'save')
->select([
'id',
'batch_code',
'status',
'grade',
'weight',
'quantity',
'commodity_name',
'warehouse',
'price',
'market_type',
'created_at',
])
->latest()
->get()
->map(function (Batch $batch) use ($user) {
return [
'id' => $batch->id,
'batch_number' => $batch->batch_code,
'status' => $batch->status,
'grade' => $batch->grade,
'weight' => $batch->weight,
'quantity' => $batch->quantity,
'commodity_name' => $batch->commodity_name,
'warehouse' => $batch->warehouse,
'price' => $batch->price,
'market_type' => $batch->market_type,
'created_at' => $batch->created_at?->toDateTimeString(),
'seller_name' => trim(($user?->fname ?? '') . ' ' . ($user?->lname ?? '')),
];
})
->values();

return Inertia::render('BatchSaved', [
'batches' => $batches,
]);
}




public function showBatchSaved(Request $request, string $id){
// Show the details page for one saved batch owned by the current user.
$batch = Batch::query()
->where('id', $id)
->where('owner_id', $request->user()->id)
->where('market_type', 'save')
->firstOrFail();

$ownerProfile = UserProfile::query()
->where('user_id', $batch->owner_id)
->first(['user_id', 'tel', 'address']);

return Inertia::render('BachSavedShow', [
'batch' => [
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
'market_type' => $batch->market_type,
'created_at' => $batch->created_at?->toDateTimeString(),
],
'owner' => [
'name' => trim(($request->user()?->fname ?? '').' '.($request->user()?->lname ?? '')),
'email' => $request->user()?->email,
'tel' => $ownerProfile?->tel,
'address' => $ownerProfile?->address,
],
]);
}






public function storeBatchProcessing(Request $request, string $id)
{
    // Validate processing form input posted from AddBatchProcess modal.
    $validated = $request->validate([
        'activity' => ['required', 'string', 'max:255', 'exists:processing_metadata,name'],
        'value' => ['required', 'string', 'max:255'],
    ]);

    // Ensure the batch exists and belongs to the authenticated owner.
    $batch = Batch::query()
        ->where('id', (int) $id)
        ->where('owner_id', $request->user()->id)
        ->firstOrFail();

    // Prevent adding the same processing activity more than once per batch.
    $activity = trim((string) $validated['activity']);
    $alreadyAdded = BatchProcessingData::query()
        ->where('batch_id', (int) $batch->id)
        ->whereRaw('LOWER(activity) = ?', [strtolower($activity)])
        ->exists();

    if ($alreadyAdded) {
        throw ValidationException::withMessages([
            'activity' => 'This processing activity already exists for this batch.',
        ]);
    }

    // Persist one processing row for the selected batch.
    BatchProcessingData::query()->create([
        'batch_id' => (int) $batch->id,
        'activity' => $activity,
        'value' => trim((string) $validated['value']),
    ]);

    // Return to the verification page so the refreshed table shows the new entry.
    return redirect()
        ->route('commodity.batch.verify', ['id' => $batch->id])
        ->with('success', 'Batch processing data saved successfully.');
}




public function storeBatchTradeActivityData(Request $request, string $id)
{
    // Validate the selected trade activity from the AddBatchTradeActivity modal.
    $validated = $request->validate([
        'activity' => ['required', 'string', 'max:255', 'exists:batch_trade_activity_metadata,activity'],
    ]);

    // Ensure the selected batch belongs to the signed-in owner before saving.
    $batch = Batch::query()
        ->where('id', (int) $id)
        ->where('owner_id', $request->user()->id)
        ->firstOrFail();

    $activity = trim((string) $validated['activity']);

    // Prevent duplicate trade activity rows for the same batch.
    $alreadyAdded = BatchTradeActivityData::query()
        ->where('batch_id', (int) $batch->id)
        ->whereRaw('LOWER(activity) = ?', [strtolower($activity)])
        ->exists();

    if ($alreadyAdded) {
        throw ValidationException::withMessages([
            'activity' => 'This trade activity already exists for this batch.',
        ]);
    }

    // Persist the selected trade activity row for this batch.
    BatchTradeActivityData::query()->create([
        'batch_id' => (int) $batch->id,
        'activity' => $activity,
        'value' => $activity,
    ]);

    return back()->with('success', 'Batch trade activity saved successfully.');
}




public function destroyBatchProcessData(Request $request, string $id, string $processingId)
{
    // Ensure batch exists and belongs to the authenticated owner.
    $batch = Batch::query()
        ->where('id', (int) $id)
        ->where('owner_id', $request->user()->id)
        ->firstOrFail();

    // Delete only the processing row that belongs to this batch.
    BatchProcessingData::query()
        ->where('id', (int) $processingId)
        ->where('batch_id', (int) $batch->id)
        ->firstOrFail()
        ->delete();

    // Send the user back so the trade activity table refreshes after deletion.
    return back()
        ->with('success', 'Batch processing data deleted successfully.');
}





public function destroyBatchCommodityData(Request $request, string $id, string $commodityId)
{
    // Ensure batch exists and belongs to the authenticated owner.
    $batch = Batch::query()
        ->where('id', (int) $id)
        ->where('owner_id', $request->user()->id)
        ->firstOrFail();

    // Remove only the selected commodity link from this batch.
    $deleted = CommodityBatch::query()
        ->where('batch_id', (int) $batch->id)
        ->where('commodity_id', (int) $commodityId)
        ->delete();

    if (!$deleted) {
        abort(404);
    }

    return redirect()
        ->route('commodity.batch.verify', ['id' => $batch->id])
        ->with('success', 'Commodity removed from batch successfully.');
}




public function batchData(Request $request, string $id, BatchService $batchService)
{
// Delegate verification-page payload assembly to the dedicated batch service.
return $batchService->batch($request, $id);
}


public function destroyBatchTradeActivityData(Request $request, string $id, string $tradeActivityId)
{
// Ensure batch exists and belongs to the authenticated owner.
$batch = Batch::query()
->where('id', (int) $id)
->where('owner_id', $request->user()->id)
->firstOrFail();

// Delete only the selected trade activity row that belongs to this batch.
BatchTradeActivityData::query()
->where('id', (int) $tradeActivityId)
->where('batch_id', (int) $batch->id)
->firstOrFail()
->delete();

return back()
->with('success', 'Batch trade activity deleted successfully.');
}




public function storeBatchToken(Request $request, string $id, TokenService $tokenService)
{
// Ensure the action only targets a batch owned by the authenticated user.
$batch = Batch::query()
->where('id', (int) $id)
->where('owner_id', $request->user()->id)
->firstOrFail();

// Keep this operation idempotent.
if (strtolower((string) $batch->status) === 'listed') {
return back()->with('success', 'Batch is already tokenized.');
}

// Create one token-chain entry for this batch using the dedicated token service.
$tokenService->addToken($batch, [
'event_type' => 'created',
'current_owner' => $batch->owner_id,
]);

// Keep batch lifecycle in sync with token creation for marketplace visibility.
$batch->update([
'status' => 'listed',
]);

return back()->with('success', 'Batch tokenized and moved to listed status successfully.');
}

















}
