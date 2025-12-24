<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'     => 'System Admin',
                'password' => Hash::make('admin@123'),
                'active'   => true, // EXPLICIT, not implicit
            ]
        );

        // Safety: ensure admin is active even if record already existed
        if (! $admin->active) {
            $admin->update(['active' => true]);
        }

        // Assign role (idempotent)
        if (! $admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }
    }
}
