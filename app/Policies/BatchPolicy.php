<?php

namespace App\Policies;

use App\Models\Batch;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BatchPolicy
{
    use HandlesAuthorization;

    /**
     * Allow administrators to bypass per-action checks.
     */
    public function before(User $user, string $ability): ?bool
    {
        return $user->role === 'admin' ? true : null;
    }

    /**
     * Determine whether the user can view any batches.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['cooperative', 'buyer'], true);
    }

    /**
     * Determine whether the user can view the batch.
     */
    public function view(User $user, Batch $batch): bool
    {
        if ((int) $batch->owner_id === (int) $user->id) {
            return true;
        }

        return $user->role === 'buyer' && $this->isMarketVisible($batch);
    }

    /**
     * Determine whether the user can create a batch.
     */
    public function create(User $user): bool
    {
        return $user->role === 'cooperative';
    }

    /**
     * Determine whether the user can update the batch.
     */
    public function update(User $user, Batch $batch): bool
    {
        return $this->ownsBatch($user, $batch);
    }

    /**
     * Determine whether the user owns the batch.
     */
    public function ownsBatch(User $user, Batch $batch): bool
    {
        return $this->ownsBatchRecord($user, $batch);
    }

    /**
     * Determine whether the user can delete the batch.
     */
    public function delete(User $user, Batch $batch): bool
    {
        return $this->ownsBatchRecord($user, $batch);
    }

    /**
     * Determine whether the user can restore the batch.
     */
    public function restore(User $user, Batch $batch): bool
    {
        return $this->ownsBatchRecord($user, $batch);
    }

    /**
     * Determine whether the user can permanently delete the batch.
     */
    public function forceDelete(User $user, Batch $batch): bool
    {
        return $this->ownsBatchRecord($user, $batch);
    }

    /**
     * Check whether the batch belongs to the current user.
     */
    protected function ownsBatchRecord(User $user, Batch $batch): bool
    {
        return (int) $batch->owner_id === (int) $user->id;
    }

    /**
     * Buyers can only see batches that are available in the market.
     */
    protected function isMarketVisible(Batch $batch): bool
    {
        return in_array((string) $batch->status, ['listed', 'tokenized', 'sold'], true)
            || in_array((string) $batch->market_type, ['marketplace', 'bidding'], true);
    }
}
