<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Screen extends Model
{
    protected $fillable = [
        'venue_id',
        'name',
        'display_name',
        'capacity',
    ];

    protected $casts = [
        'capacity' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Venue this screen belongs to
     */
    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    /**
     * Shows scheduled on this screen
     */
    public function screenSlotAssignments()
    {
        return $this->hasMany(ScreenSlotAssignment::class);
    }

    /**
     * Staff assignments for this screen
     * (many staff → same screen allowed)
     */
    public function staffAssignments()
    {
        return $this->hasMany(StaffScreenAssignment::class);
    }
}
