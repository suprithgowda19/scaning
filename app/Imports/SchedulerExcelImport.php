<?php

namespace App\Imports;

use App\Models\Scheduler;
use App\Services\SchedulerImportService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class SchedulerExcelImport implements ToCollection, WithHeadingRow
{
    protected SchedulerImportService $service;

    protected int $created = 0;
    protected int $updated = 0;
    protected int $failed  = 0;

    public function __construct()
    {
        $this->service = app(SchedulerImportService::class);
    }

    /**
     * Excel rows handler
     */
    public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $row) {
            try {
                // 1️⃣ Normalize raw Excel row
                $data = $this->normalizeRow($row->toArray());

                // 2️⃣ Validate Excel syntax & format
                $this->validateRow($data);

                // 3️⃣ Decide create vs update (idempotent import)
                $existing = Scheduler::where('show_date', $data['show_date'])
                    ->where('start_time', $data['start_time'])
                    ->whereHas('screen', function ($q) use ($data) {
                        $q->whereRaw('LOWER(name) = ?', [
                            strtolower($data['screen_name']),
                        ]);
                    })
                    ->first();

                if ($existing) {
                    $this->service->update($existing, $data);
                    $this->updated++;
                } else {
                    $this->service->create($data);
                    $this->created++;
                }

            } catch (\Throwable $e) {
                $this->failed++;

                Log::error('Scheduler Excel import failed', [
                    'row_number' => $index + 2, // header + 1-based index
                    'error'      => $e->getMessage(),
                    'row_data'   => $row->toArray(),
                ]);
            }
        }
    }

    /* ======================================================
     | NORMALIZATION (Excel quirks handled here)
     ====================================================== */

    protected function normalizeRow(array $row): array
    {
        $normalized = [];

        foreach ($row as $key => $value) {

            // Normalize header: case + spaces
            $key = strtolower(trim($key));
            $key = str_replace(' ', '_', $key);

            // Excel numeric date / time handling
            if (is_numeric($value)) {

                if ($key === 'show_date') {
                    $normalized[$key] = ExcelDate::excelToDateTimeObject($value)
                        ->format('Y-m-d');
                    continue;
                }

                if ($key === 'start_time') {
                    $normalized[$key] = ExcelDate::excelToDateTimeObject($value)
                        ->format('H:i');
                    continue;
                }
            }

            // String normalization (trim + collapse spaces)
            if (is_string($value)) {
                $value = trim($value);
                $value = preg_replace('/\s+/', ' ', $value);

                $normalized[$key] = $value === '' ? null : $value;
                continue;
            }

            $normalized[$key] = $value;
        }

        return $normalized;
    }

    /* ======================================================
     | ROW VALIDATION (STRICT)
     ====================================================== */

    protected function validateRow(array $data): void
    {
        $validator = Validator::make($data, [
            'screen_name' => ['required', 'string'],
            'venue_name'  => ['nullable', 'string'],

            'show_date'   => ['required', 'date'],
            'start_time'  => ['required', 'date_format:H:i'],

            'movie_title' => ['nullable', 'string'],
            'event_title' => ['nullable', 'string'],

            'language'    => ['nullable', 'string'],
            'duration'    => ['nullable', 'integer', 'min:1'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /* ======================================================
     | IMPORT SUMMARY
     ====================================================== */

    public function getSummary(): array
    {
        return [
            'created' => $this->created,
            'updated' => $this->updated,
            'failed'  => $this->failed,
        ];
    }
}
