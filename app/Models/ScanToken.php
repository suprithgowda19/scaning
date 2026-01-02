<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class ScanToken extends Model
{
    protected $table = 'scan_tokens';

    protected $fillable = [
        'screen_id',
        'token',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Screen this token is bound to
     */
    public function screen(): BelongsTo
    {
        return $this->belongsTo(Screen::class);
    }

    /**
     * Token validity check (optional helper)
     */
    public function isValid(): bool
    {
        return $this->expires_at === null
            || $this->expires_at->isFuture();
    }
}
