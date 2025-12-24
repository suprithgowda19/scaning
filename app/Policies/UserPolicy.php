<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Admin can view any user.
     * Staff can view only their own account.
     */
    public function view(User $authUser, User $targetUser): bool
    {
        if ($authUser->hasRole('admin')) {
            return true;
        }

        // staff → only self
        return $authUser->id === $targetUser->id;
    }
}
