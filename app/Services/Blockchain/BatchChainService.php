<?php

namespace App\Services\Blockchain;

use App\Models\Batch;
use App\Models\ChainBlock;

class BatchChainService
{
    /**
     * Append a new blockchain record for the batch.
     */
    public function addBlock(Batch $batch, array $eventData = []): ChainBlock
    {
        // Stage 1: Read the latest block under lock so index/hash sequencing stays consistent.
        $latestBlock = ChainBlock::query()
            ->select(['id', 'block_index', 'current_hash'])
            ->lockForUpdate()
            ->latest('block_index')
            ->first();

        // Stage 2: Derive the next chain position and previous hash reference.
        $blockIndex = ($latestBlock?->block_index ?? 0) + 1;
        $previousHash = $latestBlock?->current_hash ?? hash('sha256', 'GENESIS-BLOCK');
        $randomSalt = bin2hex(random_bytes(8));

        // Stage 3: Build a deterministic hash payload for this block.
        $currentHash = hash('sha256', implode('|', [
            $batch->id,
            $batch->batch_code,
            $blockIndex,
            $previousHash,
            $batch->status,
            now()->toDateTimeString(),
            $randomSalt,
        ]));

        // Stage 4: Persist the new block into the chain_blocks table.
        return ChainBlock::create([
            'batch_id' => $batch->id,
            'block_index' => $blockIndex,
            'event_type' => 'batch_created',
            'event_data' => array_merge([
                'owner_id' => $batch->owner_id,
                'batch_code' => $batch->batch_code,
                'commodity_name' => $batch->commodity_name,
                'commodity_type' => $batch->commodity_type,
                'weight' => $batch->weight,
                'grade' => $batch->grade,
                'moisture' => $batch->moisture,
                'warehouse' => $batch->warehouse,
                'status' => $batch->status,
            ], $eventData),
            'current_hash' => $currentHash,
            'previous_hash' => $previousHash,
        ]);
    }

















    







    }
