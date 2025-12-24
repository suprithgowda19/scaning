<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScanLog extends Model
{
    protected $table = 'scan_logs';

    /**
     * Enable timestamps (created_at / updated_at)
     */
    public $timestamps = true;

    /**
     * Mass assignable fields
     */
    protected $fillable = [
        'delegate_form_id',
        'uuid',
        'screen_id',

        // SSA snapshot
        'day',
        'slot_id',

        // Denormalized snapshot
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
        'day'        => 'integer',
        'slot_id'    => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships (SAFE ONLY)
    |--------------------------------------------------------------------------
    */

    public function delegate()
    {
        return $this->belongsTo(DelegateForm::class, 'delegate_form_id');
    }

    public function screen()
    {
        return $this->belongsTo(Screen::class, 'screen_id');
    }

    public function slot()
    {
        return $this->belongsTo(Slot::class, 'slot_id');
    }
}
