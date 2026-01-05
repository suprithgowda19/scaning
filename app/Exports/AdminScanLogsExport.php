<?php

namespace App\Exports;

use App\Models\ScanLog;
use App\Services\SlotResolver;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\{
    FromQuery,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithCustomValueBinder
};
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class AdminScanLogsExport extends DefaultValueBinder
    implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithCustomValueBinder
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

 
    public function query()
    {
        return ScanLog::with([
            'delegate:id,firstname,lastname,phone',
            'scheduler:id,screen_id,movie_title,show_date,start_time',
            'screen:id,name',
        ])->orderByDesc('scanned_at');
    }

    public function headings(): array
    {
        return [
            'Form No',
            'First Name',
            'Last Name',
            'Phone',
            'Screen',
            'Movie',
            'Slot',
            'Scanned At',
        ];
    }

    public function map($log): array
    {
        return [
            $log->form_no,
            $log->delegate?->firstname,
            $log->delegate?->lastname,

          
            $log->delegate?->phone,

            $log->screen?->name,
            $log->scheduler?->movie_title,
            SlotResolver::slotNoForScheduler($log->scheduler),
            $log->scanned_at,
        ];
    }

    public function bindValue(Cell $cell, $value)
    {
      
        if ($cell->getColumn() === 'D' && $value !== null) {
            $cell->setValueExplicit((string) $value, DataType::TYPE_STRING);
            return true;
        }

        return parent::bindValue($cell, $value);
    }
}
