<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Screen;

class ScanTokenMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('X-SCAN-TOKEN');

        if (! $token) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Scan token missing',
            ], 401);
        }

        /**
         * Token format (example):
         * screen_id|timestamp|HMAC
         */
        $parts = explode('|', $token);

        if (count($parts) !== 3) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid scan token',
            ], 401);
        }

        [$screenId, $timestamp, $hash] = $parts;

        // ⏱ Token expiry (example: 12 hours)
        if (abs(time() - (int) $timestamp) > 60 * 60 * 12) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Scan token expired',
            ], 401);
        }

        // 🔐 Verify HMAC
        $secret   = config('app.key');
        $expected = hash_hmac(
            'sha256',
            "{$screenId}|{$timestamp}",
            $secret
        );

        if (! hash_equals($expected, $hash)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid scan token',
            ], 401);
        }

        // 🎯 Ensure screen exists
        $screen = Screen::find($screenId);
        if (! $screen) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid screen',
            ], 401);
        }

        // 🔑 Attach resolved screen to request
        $request->attributes->set('scan_screen', $screen);

        return $next($request);
    }
}
