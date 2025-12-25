<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $fillable = [
        'external_film_id',
        'title',
        'original_title',
        'duration',
        'language',
        'country',
        'year',
        'director',
        'category',
    ];

    protected $casts = [
        'external_film_id' => 'integer',
        'duration'         => 'integer', // minutes
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * A movie can have many scheduled shows
     */
    public function screenSlotAssignments()
    {
        return $this->hasMany(ScreenSlotAssignment::class);
    }
}
