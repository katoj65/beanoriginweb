<?php

namespace App\Http\Controllers\Token;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\BatchActivityLog;
use App\Services\Buy\BuyService;

class TokenController extends Controller
{
/**
 * Render token page with batches that are already tokenized.
 */
public function index(): Response
{
$user = auth()->user();

$batches = Batch::query()
// ->where('owner_id', $user->id)
// ->whereIn('status', ['tokenized'])
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
 * Render token page with tokenized batches for the authenticated owner.
 */
public function userToken(): Response
{
$user = auth()->user();

$batches = Batch::query()
->whereIn('status', ['tokenized', 'tokenised'])
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
 * Mark a batch as listed when tokenize is triggered from verification page.
 */
public function tokenize(Request $request, string $id): RedirectResponse
{
// Ensure the action only targets a batch owned by the authenticated user.
$batch = Batch::query()
->where('id', $id)
->where('owner_id', $request->user()->id)
->firstOrFail();

// Keep the operation idempotent and avoid duplicate success updates.
if (strtolower((string) $batch->status) === 'listed') {
return redirect()
->route('commodity.batch.verify', ['id' => $batch->id])
->with('success', 'Batch is already listed.');
}

// Tokenize action should transition the batch to listed status.
$batch->update([
'status' => 'listed',
]);

return redirect()
->route('commodity.batch.verify', ['id' => $batch->id])
->with('success', 'Batch tokenized and moved to listed status successfully.');
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























}
