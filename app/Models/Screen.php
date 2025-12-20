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

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

    public function screenSlotAssignments()
    {
        return $this->hasMany(ScreenSlotAssignment::class);
    }

    public function bookings()
    {
        return $this->hasManyThrough(
            Booking::class,
            ScreenSlotAssignment::class,
            'screen_id',
            'show_id',
            'id',
            'id'
        );
    }
}
