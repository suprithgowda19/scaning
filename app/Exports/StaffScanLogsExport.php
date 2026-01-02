<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


class StaffScanLogsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected Builder $query;

    /**
     * Inject filtered query
     * Eager load delegate to avoid N+1
     */
    public function __construct(Builder $query)
    {
        $this->query = $query->with('delegate');
    }

    /**
     * Base query
     */
    public function query(): Builder
    {
        return $this->query;
    }

    /**
     * Excel column headings
     */
    public function headings(): array
    {
        return [
            'Scan ID',
            'Form No',
            'Delegate Name',
            'Mobile Number',
            'UUID',
            'Category',
            'Status',
            'Screen ID',
            'Scanned At',
            'Record Created At',
        ];
    }

    /**
     * Map each row to Excel
     */
    public function map($log): array
    {
        $delegate = $log->delegate;

        return [
            $log->id,
            $log->form_no,

            // Delegate info
            $delegate
                ? trim($delegate->firstname . ' ' . $delegate->lastname)
                : '-',

            $delegate->phone ?? '-',

            $log->uuid,
            $log->category,
            strtoupper($log->status),
            $log->screen_id,
            optional($log->scanned_at)->format('Y-m-d H:i:s'),
            optional($log->created_at)->format('Y-m-d H:i:s'),
        ];
    }
}
