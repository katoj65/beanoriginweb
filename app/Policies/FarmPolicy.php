<?php

namespace App\Policies;

use App\Models\Cooperative;
use App\Models\Farm;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FarmPolicy
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
     * Determine whether the signed-in user owns the farm through their cooperative.
     */
    public function isOwner(User $user, Farm $farm): bool
    {
        if ($user->role !== 'cooperative') {
            return false;
        }

        $cooperativeId = Cooperative::where('user_id', $user->id)->value('id');

        return $cooperativeId !== null
            && (int) $farm->farmer->cooperative_id === (int) $cooperativeId;
    }
}
