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
    ];

    /* ============================
     | Relationships
     ============================ */

    /**
     * Screen belongs to a venue
     */
    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    /**
     * Screen has many scheduler entries
     * (movies / events / seminars)
     */
    public function schedulers()
    {
        return $this->hasMany(Scheduler::class);
    }

    /**
     * Staff assigned to this screen
     */
    public function staff()
    {
        return $this->belongsToMany(
            User::class,
            'staff_screen_assignments'
        )->withTimestamps();
    }

    /**
     * Scan logs for this screen
     */
    public function scanLogs()
    {
        return $this->hasMany(ScanLog::class);
    }
}
