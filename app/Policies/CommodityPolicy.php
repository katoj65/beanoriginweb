<?php

namespace App\Policies;

use App\Models\Commodity;
use App\Models\Cooperative;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommodityPolicy
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
     * Determine whether the user can view commodity listings.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['cooperative', 'buyer', 'investor', 'organisation'], true);
    }

    /**
     * Determine whether the user can view the commodity.
     */
    public function view(User $user, Commodity $commodity): bool
    {
        if ($this->isOwner($user, $commodity)) {
            return true;
        }

        return in_array($user->role, ['buyer', 'investor', 'organisation'], true);
    }

    /**
     * Determine whether the user can create commodities.
     */
    public function create(User $user): bool
    {
        return $user->role === 'cooperative';
    }

    /**
     * Determine whether the user can update the commodity.
     */
    public function update(User $user, Commodity $commodity): bool
    {
        return $this->isOwner($user, $commodity);
    }

    /**
     * Determine whether the user can delete the commodity.
     */
    public function delete(User $user, Commodity $commodity): bool
    {
        return $this->isOwner($user, $commodity);
    }

    /**
     * Determine whether the signed-in user owns the commodity.
     */
    public function isOwner(User $user, Commodity $commodity): bool
    {
        if ($user->role !== 'cooperative') {
            return false;
        }

        $cooperativeId = Cooperative::where('user_id', $user->id)->value('id');

        return $cooperativeId !== null
            && (int) $commodity->cooperative_id === (int) $cooperativeId;
    }

    /**
     * Determine whether the user can restore the commodity.
     */
    public function restore(User $user, Commodity $commodity): bool
    {
        return $this->isOwner($user, $commodity);
    }

    /**
     * Determine whether the user can permanently delete the commodity.
     */
    public function forceDelete(User $user, Commodity $commodity): bool
    {
        return $this->isOwner($user, $commodity);
    }
}
