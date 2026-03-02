<?php

namespace App\Http\Controllers\Buy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Batch;
use App\Services\Buy\BuyService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\BatchActivityLog;

class BuyController extends Controller
{
/**
 * Display a listing of the resource.
 */
public function index()
{
//
}






/**
 * Store a newly created resource in storage.
 */
public function store(Request $request, string $id, BuyService $buyService): RedirectResponse
{
$validated = $request->validate([
'id' => ['nullable', 'integer', 'exists:batches,id'],
]);

$batchId = (int) ($validated['id'] ?? $id);
$batch = Batch::query()->findOrFail($batchId);
$buyService->ReserveBatch($batch, $request);

return back()->with('success', 'Batch selected for buy successfully.');
}








    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id): Response
    {
        $batchId = (int) $request->segment(3, $id);
        $batch = Batch::query()
            ->with('owner:id,fname,lname,email')
            ->findOrFail($batchId);

        $isReservedByUser = BatchActivityLog::query()
            ->where('batch_id', $batchId)
            ->where('user_id', (int) $request->user()->id)
            ->where('activity', 'reserved')
            ->exists();

//batch ownership check
$batchOwner=$batch->owner_id;
$userId=$request->user()->id;
$ownership=$batchOwner==$userId?true:false;

return Inertia::render('BuyBatchPage', [
            'title' => 'Buy Batch',
            'batch' => [
                'id' => $batch->id,
                'batch_code' => $batch->batch_code,
                'commodity_name' => $batch->commodity_name,
                'commodity_type' => $batch->commodity_type,
                'grade' => $batch->grade,
                'weight' => $batch->weight,
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
            'batch_ownership'=>$ownership
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
}
