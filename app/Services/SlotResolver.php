<?php

namespace App\Services;

use App\Models\Scheduler;
use Illuminate\Support\Carbon;

class SlotResolver
{
    /**
     * Slots for UI dropdown (date + optional screen)
     */
    public static function slotsForUI(?string $date, ?int $screenId = null): array
    {
        $query = Scheduler::query();

        if ($date) {
            $query->whereDate('show_date', $date);
        }

        if ($screenId) {
            $query->where('screen_id', $screenId);
        }

        return $query
            ->orderBy('start_time')
            ->get()
            ->values()
            ->map(fn ($s, $i) => [
                'slot_no' => $i + 1,
                'label'   => 'Slot ' . ($i + 1),
            ])
            ->toArray();
    }

    /**
     * Scheduler IDs for a given slot (date-first logic)
     */
    public static function schedulerIdsForSlot(
        ?string $date,
        int $slotNo,
        ?int $screenId = null
    ): array {
        $query = Scheduler::query();

        if ($date) {
            $query->whereDate('show_date', $date);
        }

        if ($screenId) {
            $query->where('screen_id', $screenId);
        }

        $scheduler = $query
            ->orderBy('start_time')
            ->get()
            ->values()
            ->get($slotNo - 1);

        return $scheduler ? [$scheduler->id] : [];
    }

    /**
     * Slot number for a given scheduler
     * Global per day (earliest = Slot 1)
     */
    public static function slotNoForScheduler(Scheduler $scheduler): int
    {
        return Scheduler::whereDate('show_date', $scheduler->show_date)
            ->orderBy('start_time')
            ->pluck('id')
            ->search($scheduler->id) + 1;
    }

    /**
     * 🔴 NEW (USED BY LIVE DASHBOARD)
     * Resolve CURRENT slot number based on time
     * Does NOT affect existing flows
     */
    public static function currentSlotForDate(string $date, Carbon $now): int
    {
        $ids = Scheduler::whereDate('show_date', $date)
            ->orderBy('start_time')
            ->pluck('id')
            ->toArray();

        if (empty($ids)) {
            return 1;
        }

        $current = Scheduler::whereDate('show_date', $date)
            ->where('start_time', '<=', $now->format('H:i:s'))
            ->orderByDesc('start_time')
            ->first();

        if (! $current) {
            return 1;
        }

        return array_search($current->id, $ids, true) + 1;
    }
}
