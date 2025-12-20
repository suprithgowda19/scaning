<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Slot extends Model
{
    protected $fillable = [
        'venue_id',
        'start_time',
    ];

    // A slot belongs to a venue
    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    // Relation to SSA (shows)
    // Not required for CRUD but good for reporting and admin UI later.
    public function screenSlotAssignments()
    {
        return $this->hasMany(ScreenSlotAssignment::class, 'slot_id');
    }
}
