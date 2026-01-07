<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DelegateCounter extends Model
{
    use HasFactory;

    protected $fillable = [
        'pickup_location',
        'period',
        'current_count',
    ];

    /**
     * No timestamps logic needed here
     * Counter updates must always be inside DB transaction
     */
}
