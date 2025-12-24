<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffScreenAssignment extends Model
{
    use HasFactory;

    protected $table = 'staff_screen_assignments';

    /**
     * Mass assignable fields.
     */
    protected $fillable = [
        'user_id',
        'venue_id',
        'screen_id',
        'active',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Assigned staff user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Venue of assignment.
     */
    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    /**
     * Screen of assignment.
     */
    public function screen()
    {
        return $this->belongsTo(Screen::class);
    }

}
