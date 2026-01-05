<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StaffScanLogsExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize
{
    protected Collection $logs;

    public function __construct(Collection $logs)
    {
        $this->logs = $logs;
    }

    public function collection(): Collection
    {
        return $this->logs;
    }

    public function headings(): array
    {
        return [
            '#',
            'Form No',
            'Name',
            'Phone',
            'Movie',
            'Slot',
            'Scanned At',
        ];
    }

    public function map($log): array
    {
        return [
            $log->id,
            $log->form_no ?? '',
            trim(($log->delegate->firstname ?? '') . ' ' . ($log->delegate->lastname ?? '')),
            $log->delegate->phone ?? '',
            $log->scheduler->movie_title ?? '',
            'Slot ' . $log->slot_no,
            $log->scanned_at,
        ];
    }
}
