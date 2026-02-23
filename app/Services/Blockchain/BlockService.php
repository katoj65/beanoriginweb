<?php

namespace App\Services\Blockchain;

use App\Models\Batch;
use App\Models\Block;
use App\Models\BlockPrice;

class BlockService
{
    /**
     * Append a new blockchain record for the batch.
     */
    public function addBlock(Batch $batch, array $eventData = []): Block
    {
        // Stage 1: Read the latest block under lock so index/hash sequencing stays consistent.
        $latestBlock = Block::query()
            ->select(['id', 'block_index', 'current_hash', 'current_owner', 'price'])
            ->lockForUpdate()
            ->latest('block_index')
            ->first();

        // Stage 2: Derive the next chain position and previous hash reference.
        $blockIndex = ($latestBlock?->block_index ?? 0) + 1;
        $previousHash = $latestBlock?->current_hash ?? hash('sha256', 'GENESIS-BLOCK');
        $randomSalt = bin2hex(random_bytes(8));

        $enteredPrice = $this->resolveEnteredPrice($batch, $eventData);
        $previousPrice = is_numeric($latestBlock?->price) ? (float) $latestBlock->price : null;
        $resolvedPrice = $this->resolveBlockPrice($enteredPrice, $previousPrice);
        $eventType = (string) ($eventData['event_type'] ?? 'batch_created');

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

        // Stage 4: Persist the new block into the blocks table.
        return Block::create([
            'batch_id' => $batch->id,
            'block_index' => $blockIndex,
            'event_type' => $eventType,
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
                'previous_price' => $previousPrice,
                'ask_price' => $enteredPrice,
                'resolved_price' => $resolvedPrice,
            ], $eventData),
            'current_hash' => $currentHash,
            'previous_hash' => $previousHash,
            'current_owner' => $batch->owner_id,
            'previous_owner' => $batch->owner_id,
            'weight' => $batch->weight,
            'price' => $resolvedPrice,
        ]);
    }

    public function addBlockPrice(int $blockID, float|string $price, ?string $currency = null): BlockPrice
    {
        $block = Block::query()->findOrFail($blockID);

        return $block->prices()->create([
            'price' => $price,
            'currency' => $currency,
        ]);
    }

    private function resolveEnteredPrice(Batch $batch, array $eventData): ?float
    {
        if (array_key_exists('entered_price', $eventData) && is_numeric($eventData['entered_price'])) {
            return (float) $eventData['entered_price'];
        }

        if (is_numeric($batch->price)) {
            return (float) $batch->price;
        }

        return null;
    }

    private function resolveBlockPrice(?float $enteredPrice, ?float $previousPrice): ?float
    {
        if ($enteredPrice === null) {
            return $previousPrice;
        }

        if ($previousPrice === null) {
            return $enteredPrice;
        }

        return round($enteredPrice, 2) === round($previousPrice, 2)
            ? $previousPrice
            : $enteredPrice;
    }
}
