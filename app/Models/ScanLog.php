<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScanLog extends Model
{
    protected $table = 'scan_logs';

    /**
     * We still keep created_at / updated_at,
     * but scanned_at is the real business timestamp
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
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships (SCHEDULER-CENTRIC)
    |--------------------------------------------------------------------------
    */

    /**
     * The show (scheduler) this scan belongs to
     */
    public function scheduler()
    {
        return $this->belongsTo(Scheduler::class);
    }

    /**
     * Delegate who was scanned
     */
    public function delegate()
    {
        return $this->belongsTo(DelegateForm::class, 'delegate_form_id');
    }

    /**
     * Screen where the scan happened
     * (denormalized for fast queries)
     */
    public function screen()
    {
        return $this->belongsTo(Screen::class, 'screen_id');
    }

    /**
     * Staff member who scanned
     */
    public function scannedBy()
    {
        return $this->belongsTo(User::class, 'scanned_by');
    }
}
