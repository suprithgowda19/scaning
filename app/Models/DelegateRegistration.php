<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DelegateRegistration extends Model
{
    use HasFactory;

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        // Generated after payment
        'registration_code',
        'reference_id',
        'form_no',
        'uuid',

        // Personal
        'first_name',
        'last_name',
        'dob',
        'age',
        'gender',

        // Contact
        'email',
        'country_code',
        'phone',

        // Address
        'address',
        'city',
        'pincode',
        'country',

        // Identity
        'id_type',
        'id_number',
        'id_document_path',

        // Category & fee
        'category',
        'fee_amount',

        // Media
        'photo_path',
        'id_card_path',

        // Pickup
        'pickup_location',
        'pickup_date',

        // State
        'status',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'dob'         => 'date',
        'pickup_date' => 'date',
        'is_agree'    => 'boolean',
    ];

    /**
     * Relationships
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Scopes
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopePaid($query)
    {
        return $query->whereIn('status', ['confirmed']);
    }
}
