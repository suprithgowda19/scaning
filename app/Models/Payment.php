<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'delegate_id',
        'amount',
        'currency',
        'status',
        'provider',
        'gateway_order_id',
        'gateway_payment_id',
        'paid_at',
        'failure_reason',
    ];

    protected $casts = [
        'amount'  => 'integer',
        'paid_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function delegate(): BelongsTo
    {
        return $this->belongsTo(Delegate::class);
    }

    /**
     * Domain helpers (optional but clean)
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isInitiated(): bool
    {
        return $this->status === 'initiated';
    }
}
