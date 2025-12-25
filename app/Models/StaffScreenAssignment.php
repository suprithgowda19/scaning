<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffScreenAssignment extends Model
{
    protected $table = 'staff_screen_assignments';

    protected $fillable = [
        'user_id',
        'venue_id',
        'screen_id',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function screen(): BelongsTo
    {
        return $this->belongsTo(Screen::class);
    }
}
