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
    'scheduler_id',
    'delegate_form_id',
    'uuid',
    'form_no',
    'category',
    'screen_id',
    'scanned_by',
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
    public function screenSlotAssignment()
    {
        return $this->belongsTo(ScreenSlotAssignment::class, 'day', 'day')
            ->whereColumn('screen_id', 'screen_id')
            ->whereColumn('slot_id', 'slot_id');
    }
}
