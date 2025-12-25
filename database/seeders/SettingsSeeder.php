<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $settings = [
            [
                'key'         => 'scan_grace_before',
                'value'       => '30',
                'description' => 'Minutes before slot start when scanning opens',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'key'         => 'scan_grace_after',
                'value'       => '15',
                'description' => 'Minutes after slot end when scanning closes',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
        ];

        DB::table('settings')->upsert(
            $settings,
            ['key'],          // unique constraint
            ['value', 'description', 'updated_at']
        );
    }
}
