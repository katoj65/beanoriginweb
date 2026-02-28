<?php

namespace App\Http\Controllers\Token;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use Inertia\Inertia;
use Inertia\Response;

class TokenController extends Controller
{
    /**
     * Render token page with batches that are already tokenized.
     */
    public function index(): Response
    {
        $user = auth()->user();

        $batches = Batch::query()
            ->where('owner_id', $user->id)
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
