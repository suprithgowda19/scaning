<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    /**
     * Screens assigned to staff
     */
    public function screens(): BelongsToMany
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

    /**
     * Convenience helper
     */
    public function activeScreen()
    {
        return $this->screens()->first();
    }

    /**
     * Role helper
     */
    public function isStaff(): bool
    {
        return $this->hasRole('staff');
    }
}
