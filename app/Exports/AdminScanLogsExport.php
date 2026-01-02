<?php

namespace App\Exports;

use App\Models\ScanLog;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\{
    FromQuery,
    WithHeadings,
    WithMapping,
    ShouldAutoSize
};

class AdminScanLogsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Base export query (same filters as dashboard)
     */
    public function query()
    {
        $query = ScanLog::with([
            'delegate:id,firstname,lastname,phone',
            'screen:id,name',
            'slot:id,start_time',
            'screenSlotAssignment.movie:id,title',
        ]);

        if ($this->request->filled('day')) {
            $query->where('day', $this->request->day);
        }

        if ($this->request->filled('screen_id')) {
            $query->where('screen_id', $this->request->screen_id);
        }

        if ($this->request->filled('slot_id')) {
            $query->where('slot_id', $this->request->slot_id);
        }

        if ($this->request->filled('movie_id')) {
            $query->whereHas('screenSlotAssignment', function ($q) {
                $q->where('movie_id', $this->request->movie_id);
            });
        }

        return $query->orderByDesc('scanned_at');
    }

    /**
     * Excel column headings
     */
    public function headings(): array
    {
        return [
            'Form No',
            'Name',
            'Phone',
            'Category',
            'Screen',
            'Movie',
            'Slot',
            'Day',
            'Scanned At',
        ];
    }

    /**
     * Row mapping
     */
    public function map($log): array
    {
        return [
            $log->form_no,
            optional($log->delegate)
                ? trim($log->delegate->firstname . ' ' . $log->delegate->lastname)
                : '-',
            optional($log->delegate)->phone ?? '-',
            $log->category,
            optional($log->screen)->name,
            optional($log->screenSlotAssignment?->movie)->title,
            optional($log->slot)->start_time,
            $log->day,
            optional($log->scanned_at)->format('Y-m-d H:i:s'),
        ];
    }
}
