<?php

namespace App\Http\Controllers\Batch;

use App\Http\Controllers\Controller;
use App\Http\Resources\BatchResource;
use App\Models\Batch;
use App\Models\BatchActivity;
use App\Models\CropGrade;
use App\Models\Crops;
use App\Models\UserProfile;
use App\Services\Blockchain\BatchChainService;
use App\Services\Blockchain\BlockService;
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
$crops = Crops::query()
->orderBy('name')
->get(['id', 'name']);

$grades = CropGrade::query()
->orderBy('name')
->get(['id', 'name']);

return Inertia::render('BatchCreate', [
'crops' => $crops,
'grades' => $grades,
'status_options' => ['created', 'processing', 'processed', 'hulled', 'graded', 'listed', 'sold'],
]);
}






/**
 * Store a newly created resource in storage.
 */
public function store(Request $request, BatchChainService $batchChainService, BlockService $blockService)
{

$validated = $request->validate([
'batch_code' => ['required', 'string', 'max:255', 'unique:batches,batch_code'],
'commodity_name' => ['required', 'string', 'max:255', 'exists:crops,name'],
'commodity_type' => ['required', 'string', 'max:255'],
'weight' => ['required', 'numeric', 'min:0.01'],
'price' => ['required', 'numeric', 'min:0.01'],
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
'grade' => $validated['grade'],
'moisture' => $validated['moisture'] ?? null,
'warehouse' => $validated['warehouse'],
'is_on_chain' => false,
'status' => 'created',
'price' => $validated['price'],

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


$batch = Batch::query()
->with('owner:id,fname,lname,email')
->where('owner_id', auth()->id())
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
'price' => $validated['price'],
'grade' => $validated['grade'],
'moisture' => $validated['moisture'] ?? null,
'warehouse' => $validated['warehouse'],
];

// Prevent no-op updates and return a form-level validation error.
$numericFields = ['weight', 'price', 'moisture'];
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
public function batchUnlisted(Request $request)
{
$user = $request->user();

$batches = Batch::query()
->where('owner_id', $user->id)
->where('status', 'created')
->where(function ($query) {
$query->where('is_on_chain', 0);
})
->latest()
->get()
->map(function (Batch $batch) use ($user) {
return [
'id' => $batch->id,
'batch_number' => $batch->batch_code,
'status' => $batch->status,
'grade' => $batch->grade,
'weight' => $batch->weight,
'commodity_name' => $batch->commodity_name,
'warehouse' => $batch->warehouse,
'price' => $batch->price,
'created_at' => $batch->created_at?->toDateTimeString(),
'seller_name' => trim(($user?->fname ?? '') . ' ' . ($user?->lname ?? '')),
];
})
->values();

return Inertia::render('BatchUnlistedPage', [
'batches' => $batches,
]);
}












}
