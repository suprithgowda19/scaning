<?php

namespace App\Services;

use App\Models\User;
use App\Models\Screen;

class ScanContextService
{
    /**
     * Resolve active screen for staff
     */
    public function resolveScreen(User $user): Screen
    {
        return $user->screens()
            ->wherePivot('active', 1)
            ->firstOrFail();
    }

    /**
     * Issue stateless scan token
     *
     * Token = HMAC(staff_id|screen_id)
     */
    public function issueToken(User $user, Screen $screen): string
    {
        return hash_hmac(
            'sha256',
            $user->id . '|' . $screen->id,
            config('app.key')
        );
    }

    /**
     * Verify token against staff + screen
     */
    public function verifyToken(string $token, User $user, Screen $screen): bool
    {
        return hash_equals(
            $this->issueToken($user, $screen),
            $token
        );
    }
}
