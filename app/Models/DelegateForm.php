<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DelegateForm extends Model
{
    use HasFactory;

    /**
     * Legacy table
     */
    protected $table = 'delegate_forms';

    /**
     * Legacy primary key
     * DO NOT change – imports depend on this
     */
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    /**
     * Legacy timestamps exist
     */
    public $timestamps = true;

    /**
     * Mass assignment protection
     * Keep tight – scanning system is not CRUD playground
     */
    protected $fillable = [
        'form_no',
        'form_nos',
        'reference_id',
        'firstname',
        'lastname',
        'email',
        'phone',
        'category',
        'payment',
        'payment_status',
        'form_status',
        'uuid',
        'id_card_image',
        'qr_count',
    ];

    /**
     * Casting for correctness
     */
    protected $casts = [
        'is_agree'   => 'boolean',
        'amount'     => 'decimal:2',
        'dob'        => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Scan logs for this delegate
     * FK is soft (no DB enforcement)
     */
    public function scanLogs()
    {
        return $this->hasMany(ScanLog::class, 'delegate_form_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes — USE THESE EVERYWHERE
    |--------------------------------------------------------------------------
    */

    /**
     * Primary scan resolver (QR authority)
     */
    public function scopeByUuid($query, string $uuid)
    {
        return $query->where('uuid', $uuid);
    }

    /**
     * Admin / manual lookup
     */
    public function scopeByFormNo($query, string $formNo)
    {
        return $query->where('form_no', $formNo);
    }

    /**
     * External reconciliation (payments / exports)
     */
    public function scopeByReferenceId($query, string $referenceId)
    {
        return $query->where('reference_id', $referenceId);
    }

    /**
     * Delegates that can be scanned
     * (keep permissive for now)
     */
    public function scopeScannable($query)
    {
        return $query->whereNotNull('uuid');
    }

    /*
    |--------------------------------------------------------------------------
    | Business Logic
    |--------------------------------------------------------------------------
    */

    /**
     * Has this delegate already been scanned anywhere?
     * Global duplicate check
     */
    public function alreadyScanned(): bool
    {
        return $this->scanLogs()->exists();
    }

    /**
     * Display name helper (UI / logs)
     */
    public function getFullNameAttribute(): string
    {
        return trim("{$this->firstname} {$this->lastname}");
    }
}
