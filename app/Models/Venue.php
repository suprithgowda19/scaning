<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * A venue has many screens.
     */
    public function screens()
    {
        return $this->hasMany(Screen::class);
    }

    /**
     * Staff assigned to this venue (derived via screens).
     * Venue-aware and active-only.
     */
    public function staff()
    {
        return $this->belongsToMany(
            User::class,
            'staff_screen_assignments'
        )
        ->withPivot('screen_id', 'active')
        ->wherePivot('active', true)
        ->distinct();
    }
}
