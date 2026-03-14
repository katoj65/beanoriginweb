<?php

namespace App\Services\Token;

use App\Models\Batch;
use App\Models\Token;

class TokenService
{
    /**
     * Create a token record for the batch and advance the token chain.
     */
    public function addToken(Batch $batch, array $eventData = []): Token
    {
        $latestToken = Token::query()
            ->select(['id', 'batch_id', 'token_index', 'current_hash', 'current_owner'])
            ->lockForUpdate()
            ->latest('token_index')
            ->first();

        $tokenIndex = ($latestToken?->token_index ?? 0) + 1;
        $previousHash = $latestToken?->current_hash ?? hash('sha256', 'GENESIS-TOKEN');
        $randomSalt = bin2hex(random_bytes(8));
        $eventType = (string) ($eventData['event_type'] ?? 'created');
        $resolvedCurrentOwner = $this->resolveOwnerId($eventData['current_owner'] ?? null, (int) $batch->owner_id);
        $resolvedPreviousOwner = $this->resolveOwnerId(
            $eventData['previous_owner'] ?? $latestToken?->current_owner,
            (int) $batch->owner_id
        );

        if ($latestToken && (int) $latestToken->batch_id === (int) $batch->id) {
            Token::query()
                ->whereKey($latestToken->id)
                ->update(['status' => 'redeemed']);
        }

        $currentHash = hash('sha256', implode('|', [
            $batch->id,
            $batch->batch_code,
            $tokenIndex,
            $previousHash,
            $eventType,
            $resolvedCurrentOwner,
            now()->toDateTimeString(),
            $randomSalt,
        ]));

        return Token::create([
            'batch_id' => $batch->id,
            'token_index' => $tokenIndex,
            'event_type' => $eventType,
            'metadata' => array_merge([
                'batch_code' => $batch->batch_code,
                'commodity_name' => $batch->commodity_name,
                'commodity_type' => $batch->commodity_type,
                'grade' => $batch->grade,
                'moisture' => $batch->moisture,
                'warehouse' => $batch->warehouse,
                'status' => $batch->status,
                'current_owner' => $resolvedCurrentOwner,
                'previous_owner' => $resolvedPreviousOwner,
            ], $eventData),
            'current_hash' => $currentHash,
            'previous_hash' => $previousHash,
            'current_owner' => $resolvedCurrentOwner,
            'previous_owner' => $resolvedPreviousOwner,
            'status' => $this->resolveStatus($eventData['status'] ?? null),
        ]);
    }

    /**
     * Backward-compatible wrapper while callers are moved over to token naming.
     */
    public function addBlock(Batch $batch, array $eventData = []): Token
    {
        return $this->addToken($batch, $eventData);
    }

    private function resolveOwnerId(mixed $candidate, int $fallback): int
    {
        if ($candidate === null || $candidate === '') {
            return $fallback;
        }

        if (is_numeric($candidate) && (int) $candidate > 0) {
            return (int) $candidate;
        }

        return $fallback;
    }

    private function resolveStatus(mixed $status): string
    {
        return $status === 'redeemed' ? 'redeemed' : 'active';
    }
}
