<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class StaffScreenAssignment extends Model
{
    protected $fillable = [
        'user_id',
        'venue_id',
        'screen_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function screen()
    {
        return $this->belongsTo(Screen::class);
    }
}
