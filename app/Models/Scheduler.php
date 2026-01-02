<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Scheduler extends Model
{
    use HasFactory;

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'venue_id',
        'screen_id',
        'movie_title',
        'event_title',
        'language',
        'duration',
        'show_date',
        'start_time',
        'is_active',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'show_date' => 'date',
        'start_time' => 'datetime:H:i',
        'duration' => 'integer',
        'is_active' => 'boolean',
    ];

    /* ============================
     | Relationships
     ============================ */

    /**
     * Scheduler belongs to a venue
     */
    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    /**
     * Scheduler belongs to a screen
     */
    public function screen()
    {
        return $this->belongsTo(Screen::class);
    }

    /* ============================
     | Read-only Helpers (NO LOGIC)
     ============================ */

    /**
     * Is this a movie schedule?
     */
    public function isMovie(): bool
    {
        return ! is_null($this->movie_title);
    }

    /**
     * Is this an event schedule?
     */
    public function isEvent(): bool
    {
        return ! is_null($this->event_title);
    }

    /**
     * Display title (safe helper)
     */
    public function getTitleAttribute(): string
    {
        return $this->movie_title ?? $this->event_title;
    }

    /* ============================
     | Query Scopes (Read-only)
     ============================ */

    /**
     * Scope by venue
     */
    public function scopeForVenue($query, int $venueId)
    {
        return $query->where('venue_id', $venueId);
    }

    /**
     * Scope active schedules
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
