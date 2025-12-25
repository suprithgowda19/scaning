<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
    protected $fillable = [
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'start_time' => 'string',
        'end_time'   => 'string',
    ];

    public function screenSlotAssignments()
    {
        return $this->hasMany(ScreenSlotAssignment::class);
    }
}
