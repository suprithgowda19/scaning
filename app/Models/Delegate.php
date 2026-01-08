<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// ADD THIS IMPORT BELOW
use Illuminate\Database\Eloquent\Relations\HasMany;

class Delegate extends Model
{
    protected $fillable = [
        // ... (all your fields)
        'first_name', 'last_name', 'email', 'phone', 'country_code', 'dob', 'age', 'gender',
        'id_type', 'id_number', 'address', 'city', 'pincode', 'country', 'category',
        'pickup_location', 'id_document_path', 'id_document_mime', 'id_document_size',
        'photo_path', 'photo_mime', 'photo_size', 'status', 'uuid', 'form_no', 'reference_id',
    ];

    protected $casts = [
        'dob' => 'date',
    ];

    protected $attributes = [
        'status' => 'draft',
    ];

    /**
     * Relationship: A delegate can have multiple payment attempts
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}