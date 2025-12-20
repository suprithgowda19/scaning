<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScreenSlotAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'venue_id',
        'screen_id',
        'slot_id',
        'movie_id',
        'day',
        'status',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function screen()
    {
        return $this->belongsTo(Screen::class);
    }

    public function slot()
    {
        return $this->belongsTo(Slot::class);
    }

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
}
