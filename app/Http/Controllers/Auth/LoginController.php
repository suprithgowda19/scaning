<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Default fallback (not really used because we override redirect)
     */
    protected $redirectTo = '/login';

    /**
     * Runs AFTER successful authentication
     * but BEFORE redirect.
     */
    protected function authenticated(Request $request, $user)
    {
        // 1️ Block inactive users
        if (!$user->active) {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => 'Your account is inactive. Please contact administrator.',
            ]);
        }

        // 2Role-based redirection
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.users.index');
        }

        if ($user->hasRole('staff')) {
            return redirect()->route('dashboard.staff.index');
        }

        // 3️ Safety fallback (should never happen)
        Auth::logout();

        throw ValidationException::withMessages([
            'email' => 'You do not have access to this system.',
        ]);
    }
}
