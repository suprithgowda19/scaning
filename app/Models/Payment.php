<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'delegate_registration_id',
        'gateway',
        'order_id',
        'payment_id',
        'signature',
        'amount',
        'status',
        'gateway_payload',
    ];

    protected $casts = [
        'gateway_payload' => 'array',
    ];

    /**
     * Relationships
     */
    public function registration()
    {
        return $this->belongsTo(DelegateRegistration::class, 'delegate_registration_id');
    }

    /**
     * Scopes
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }
}
