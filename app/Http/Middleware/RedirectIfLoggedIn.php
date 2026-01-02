<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfLoggedIn
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {

            $previous = url()->previous();

            if ($previous && $previous !== route('login')) {
                return redirect()->to($previous);
            }

            return redirect()->back();
        }

        return $next($request);
    }
}
