<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // ✅ REQUIRED — your code expects this
    public function screens()
    {
        return $this->belongsToMany(
            Screen::class,
            'staff_screen_assignments',
            'user_id',
            'screen_id'
        )
            ->withPivot('venue_id', 'active')
            ->wherePivot('active', true);
    }

    // ✅ convenience helper
    public function activeScreen()
    {
        return $this->screens()->first();
    }

    /*
    |--------------------------------------------------------------------------
    | Role helpers
    |--------------------------------------------------------------------------
    */

    public function isStaff(): bool
    {
        return $this->hasRole('staff');
    }
}
