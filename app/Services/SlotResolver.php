<?php

namespace App\Services;

use App\Models\Scheduler;

class SlotResolver
{
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
     * 🔑 SLOT NUMBER FOR A GIVEN SCHEDULER
     * Global per day (earliest = Slot 1)
     */
    public static function slotNoForScheduler(Scheduler $scheduler): int
    {
        return Scheduler::whereDate('show_date', $scheduler->show_date)
            ->orderBy('start_time')
            ->pluck('id')
            ->search($scheduler->id) + 1;
    }
}
