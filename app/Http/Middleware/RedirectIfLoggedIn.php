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

            // If previous page exists AND is not login itself
            if ($previous && $previous !== route('login')) {
                return redirect()->to($previous);
            }

            // If login was opened directly (new tab / refresh),
            // just go back in browser history
            return redirect()->back();
        }

        return $next($request);
    }
}
