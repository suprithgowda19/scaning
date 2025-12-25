<?php

namespace App\Services;

use App\Models\ScreenSlotAssignment;
use Carbon\Carbon;

class ScanWindowService
{
    public function isOpen(ScreenSlotAssignment $ssa, ?Carbon $now = null): bool
    {
        $now = $now ?? now();

        $slot = $ssa->slot;

        // Build start & end using show date
        $start = Carbon::parse($ssa->show_date)
            ->setTimeFromTimeString($slot->start_time)
            ->subMinutes(Settings::int('scan_grace_before'));

        $end = Carbon::parse($ssa->show_date)
            ->setTimeFromTimeString($slot->end_time)
            ->addMinutes(Settings::int('scan_grace_after'));

        // Midnight-crossing slot safety
        if ($end->lessThan($start)) {
            $end->addDay();
        }

        return $now->between($start, $end);
    }
}
