<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class Settings
{
    public static function int(string $key, int $default = 0): int
    {
        return (int) Cache::remember(
            "settings:$key",
            3600,
            fn () => DB::table('settings')
                ->where('key', $key)
                ->value('value') ?? $default
        );
    }
}
