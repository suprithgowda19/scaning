<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScanLog extends Model
{
    protected $table = 'scan_logs';

    /**
     * IMPORTANT
     * We are manually setting scanned_at,
     * so disable Laravel auto timestamps.
     */
    public $timestamps = false;

    /**
     * Mass assignable fields
     * Must exactly match controller usage.
     */
    protected $fillable = [
        'delegate_form_id',
        'uuid',
        'screen_id',
        'scanned_by',
        'form_no',
        'category',
        'status',
        'scanned_at',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'scanned_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Scanned delegate
     */
    public function delegate()
    {
        return $this->belongsTo(
            DelegateForm::class,
            'delegate_form_id'
        );
    }

    /**
     * Screen where scan happened
     */
    public function screen()
    {
        return $this->belongsTo(
            Screen::class,
            'screen_id'
        );
    }

    /**
     * Staff user who scanned
     */
    public function staff()
    {
        return $this->belongsTo(
            User::class,
            'scanned_by'
        );
    }
}
