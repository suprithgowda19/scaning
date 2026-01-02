<?php

namespace App\Services;

use App\Models\ScanToken;
use App\Models\Screen;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ScanTokenResolver
{
    public function resolveScreen(string $token): Screen
    {
        $record = ScanToken::where('token', $token)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->first();

        if (! $record) {
            throw new HttpException(403, 'Invalid or expired scan token');
        }

        return $record->screen;
    }
}
