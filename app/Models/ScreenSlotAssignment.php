<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScreenSlotAssignment extends Model
{
    protected $fillable = [
        'screen_id',
        'slot_id',
        'movie_id',
        'show_date',
    ];

    protected $casts = [
        'show_date' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Physical screen where the show runs
     */
    public function screen()
    {
        return $this->belongsTo(Screen::class);
    }

    /**
     * Global time slot (start_time + end_time)
     */
    public function slot()
    {
        return $this->belongsTo(Slot::class);
    }

    /**
     * Movie being screened
     */
    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
}
