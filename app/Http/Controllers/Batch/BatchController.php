<?php

namespace App\Http\Controllers\Batch;

use App\Http\Controllers\Controller;
use App\Http\Resources\BatchResource;
use App\Models\Batch;
use App\Models\BatchActionList;
use App\Models\CropGrade;
use App\Models\Crops;
use App\Models\UserProfile;
use App\Services\Blockchain\BatchChainService;
use App\Services\Blockchain\BlockService;
use App\Models\Block;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;


class BatchController extends Controller
{
/**
 * Display a listing of the resource.
 */
public function index()
{
$user = auth()->user();

$latestBlocks = DB::table('blocks')
->select('batch_id', DB::raw('MAX(block_index) as latest_block_index'))
->groupBy('batch_id');

$latestPrices = DB::table('block_prices')
->select('block_id', DB::raw('MAX(id) as latest_price_id'))
->groupBy('block_id');



$batches = DB::table('batches')
->leftJoinSub($latestBlocks, 'latest_blocks', function ($join) {
$join->on('latest_blocks.batch_id', '=', 'batches.id');
})
->leftJoin('blocks', function ($join) {
$join->on('blocks.batch_id', '=', 'batches.id')
->on('blocks.block_index', '=', 'latest_blocks.latest_block_index');
})
->leftJoinSub($latestPrices, 'latest_prices', function ($join) {
$join->on('latest_prices.block_id', '=', 'blocks.id');
})
->leftJoin('block_prices', 'block_prices.id', '=', 'latest_prices.latest_price_id')
->where('batches.owner_id', auth()->id())
->where(function ($query) {
$query->where('batches.is_on_chain', 'true')
->orWhere('batches.is_on_chain', 1)
->orWhere('batches.is_on_chain', '1');
})
->orderByDesc('batches.created_at')
->get([
'batches.id',
'batches.batch_code as batch_number',
'batches.status',
'batches.grade',
'batches.weight',
'batches.created_at',
'batches.commodity_name',
'blocks.current_hash as latest_block_hash',
'blocks.block_index as chain_height',
'block_prices.price as ask_price',
'block_prices.currency',
])
->map(function ($row) use ($user) {
return [
'id' => (int) $row->id,
'batch_number' => $row->batch_number,
'status' => $row->status,
'grade' => $row->grade,
'weight' => $row->weight,
'created_at' => $row->created_at,
'listed_at' => $row->created_at,
'commodity_id' => null,
'commodity_names' => $row->commodity_name ? [$row->commodity_name] : [],
'commodity_count' => $row->commodity_name ? 1 : 0,
'seller_name' => trim(($user?->fname ?? '') . ' ' . ($user?->lname ?? '')),
'latest_block_hash' => $row->latest_block_hash,
'chain_height' => $row->chain_height ? (int) $row->chain_height : null,
'ask_price' => $row->ask_price,
'currency' => $row->currency,
];
})
->values();

$batchActionList = BatchActionList::query()
->where('name', '!=', 'created')
->orderBy('name')
->get(['id', 'name']);

return Inertia::render('BatchListed', [
'batches' => $batches,
'batch_action_list' => $batchActionList,
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
'price' => ['nullable', 'numeric', 'min:0.01'],
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
'is_on_chain' => 'false',
'status' => 'created',
'price' => $validated['price'] ?? null,

]);

// Set previous owner to the creator for the initial block.

// $batchChainService->addBlock($batch);
// $blockService->addBlockPrice($block->id, (float) ($validated['price'] ?? 0));
// Initial price can be null or set to a default value.
// $batch->update(['is_on_chain' => true]);
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
'event_type' => 'batch_listed',
'action' => 'listed_on_chain',
'entered_price' => $validated['price'],
]);

// $blockService->addBlockPrice($block->id, (float) ($block->price ?? $validated['price']));

});

return redirect()
->route('cooperative.batches.show', ['id' => $batch->id])
->with('success', 'Batch listed on chain successfully.');




}













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
'batch_number' => $batch->batch_code,
],
'batch' => new BatchResource($batch),
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
