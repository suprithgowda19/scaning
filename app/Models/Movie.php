<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        'title',
        'language',
        'poster_path',
        'duration',
        'description',
        'status',
    ];

    /**
     * Accessor: returns full poster URL (local or CDN ready).
     */
    public function getPosterUrlAttribute()
    {
        if (!$this->poster_path) {
            return asset('assets/images/no-poster.png'); // fallback
        }

        return asset('storage/' . $this->poster_path);
    }

    /**
     * Scope: only active movies.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
