<?php

namespace App\Http\Controllers\Batch;

use App\Http\Controllers\Controller;
use App\Http\Resources\BatchResource;
use App\Models\Batch;
use App\Models\BatchActivity;
use App\Models\BatchActionList;
use App\Models\CropGrade;
use App\Models\Crops;
use App\Models\UserProfile;
use App\Services\Blockchain\BatchChainService;
use App\Services\Blockchain\BlockService;
use App\Models\Block;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\BatchBlockResource;
use App\Http\Resources\BlockBatchLatestResource;
use Inertia\Inertia;


class BatchController extends Controller
{
/**
 * Display a listing of the resource.
 */
public function index()
{

$user = auth()->user();
$baches = Block::query()
->select('batches.id',
'batches.commodity_name',
'batches.batch_code',
'blocks.created_at',
'blocks.price',
'blocks.weight',
'blocks.event_type',
'blocks.event_data')
->join('batches', 'blocks.batch_id', '=', 'batches.id')
->where('blocks.current_owner', $user->id)
->where('blocks.event_type', 'listed')
->whereIn('blocks.block_index', function ($q) use ($user) {
$q->from('blocks')
->selectRaw('MAX(block_index)')
->where('current_owner', $user->id)
->where('event_type', 'listed')
->groupBy('batch_id');
})
->get();

return Inertia::render('BatchListed', [
'batches' => BlockBatchLatestResource::collection($baches),
'batch_action_list' => [],
]);
}





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







// Custom method to handle batch verification action form submission.
public function batchVerificationAction(Request $request)
{
$validate = $request->validate([
'batch_number' => ['required', 'string', 'max:255'],
'batch_action' => ['required', 'string', 'max:255'],
]);

$batch = Batch::query()
->where('batch_code', $validate['batch_number'])
->where('owner_id', auth()->id())
->where('status', 'created')
->first();

if (!$batch) {
return back()->withErrors([
'batch_number' => 'Batch not found.',
])->withInput();
}

return redirect()->route('cooperative.batches.action.page', [
'batch_id' => $batch->id,
'batch_action' => $validate['batch_action'],
]);
}








// Render summary page for selected batch and intended action.
public function BatchActionPage(Request $request)
{
$batchId = (int) $request->input('batch_id');
$selectedAction = (string) $request->input('batch_action', '');

$batch = Batch::query()
->where('id', $batchId)
->where('owner_id', auth()->id())
->where('status','!=', 'bought')
->first();

if (!$batch) {
return redirect()
->route('cooperative.batches.listed')
->withErrors(['batch_number' => 'Batch not found for action summary.']);
}

return Inertia::render('BatchActionPage', [
'selection' => [
'selected_action' => $selectedAction,
'batch_id' => $batch->id,
'batch_number' => $batch->batch_code,
],
'batch' => new BatchResource($batch),
'back_to_verification_url' => route('commodity.batch.verify', ['id' => $batch->id]),
]);
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
