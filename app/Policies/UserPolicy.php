<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Check whether the signed-in user belongs to one of the allowed business roles.
     */
    public function accessPlatform(User $user): bool
    {
        return in_array($user->role, [
            'cooperative',
            'admin',
            'buyer',
            'investor',
            'organisation',
        ], true);
    }

    public function isCooperative(User $user): bool
    {
        return $user->role === 'cooperative';
    }

    public function isAdmin(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function isBuyer(User $user): bool
    {
        return $user->role === 'buyer';
    }

    public function isFarmer(User $user): bool
    {
        return $user->role === 'farmer';
    }

    public function isInvestor(User $user): bool
    {
        return $user->role === 'investor';
    }

    public function isOrganisation(User $user): bool
    {
        return $user->role === 'organisation';
    }
}
