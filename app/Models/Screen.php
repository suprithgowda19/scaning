<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Screen extends Model
{
    use HasFactory;

    protected $fillable = [
        'venue_id',
        'name',
        'capacity',
        'status',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Screen belongs to a venue.
     */
    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    /**
     * Staff assigned to this screen (venue-aware).
     * CRITICAL for scanning & access control.
     */
    public function staff()
    {
        return $this->belongsToMany(
            User::class,
            'staff_screen_assignments'
        )
        ->withPivot('venue_id', 'active')
        ->wherePivot('active', true);
    }

    /**
     * Scan logs for this screen.
     */
    public function scanLogs()
    {
        return $this->hasMany(ScanLog::class);
    }

    /**
     * Seats in this screen.
     */
    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

    /**
     * Screen-slot assignments (shows).
     */
    public function screenSlotAssignments()
    {
        return $this->hasMany(ScreenSlotAssignment::class);
    }

    /**
     * Bookings via screen-slot assignments.
     */
    public function bookings()
    {
        return $this->hasManyThrough(
            Booking::class,
            ScreenSlotAssignment::class,
            'screen_id',   // FK on SSA
            'show_id',     // FK on bookings
            'id',          // local key on screens
            'id'           // local key on SSA
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Capacity helpers (READ-ONLY HELPERS)
    |--------------------------------------------------------------------------
    */

    /**
     * Total entries scanned for this screen.
     * DISPLAY / REPORTING ONLY.
     */
    public function enteredCount(): int
    {
        return $this->scanLogs()->count();
    }

    /**
     * Remaining capacity.
     */
    public function vacantCount(): int
    {
        return max(0, $this->capacity - $this->enteredCount());
    }

    /**
     * Whether this screen can accept more entries.
     * DO NOT use as the sole concurrency gate.
     */
    public function hasCapacity(): bool
    {
        return $this->enteredCount() < $this->capacity;
    }
}
