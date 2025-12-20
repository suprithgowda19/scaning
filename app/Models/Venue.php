<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
      
    ];

   

    /**
     * A venue has many screens.
     */
    public function screens()
    {
        return $this->hasMany(Screen::class);
    }
}
