<?php

namespace App\Imports;

use App\Models\Scheduler;
use App\Models\Screen;
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
    protected int $skipped = 0;
    protected int $failed  = 0;

    /**
     * Normalized screen_name => screen_id
     */
    protected array $screenMap = [];

    public function __construct()
    {
        $this->service = app(SchedulerImportService::class);
        $this->loadScreenMap();
    }

    protected function loadScreenMap(): void
    {
        foreach (Screen::all(['id', 'name']) as $screen) {
            $this->screenMap[$this->cleanString($screen->name)] = $screen->id;
        }
    }

    public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $row) {
            try {
                $data = $this->normalizeRow($row->toArray());

                // Resolve screen_id
                $screenKey = $this->cleanString($data['screen_name'] ?? '');

                if (! isset($this->screenMap[$screenKey])) {
                    throw new \Exception("Unknown screen: {$data['screen_name']}");
                }

                $data['screen_id'] = $this->screenMap[$screenKey];

                $this->validateRow($data);

                // 🚨 STRICT SLOT CHECK (NO UPDATE)
                $exists = Scheduler::where('screen_id', $data['screen_id'])
                    ->where('show_date', $data['show_date'])
                    ->where('start_time', $data['start_time'])
                    ->exists();

                if ($exists) {
                    $this->skipped++;
                    continue;
                }

                $this->service->create($data);
                $this->created++;

            } catch (\Throwable $e) {
                $this->failed++;

                Log::warning('Scheduler Import Row Failed', [
                    'row'   => $index + 2,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    protected function cleanString(?string $text): string
    {
        return strtolower(trim(preg_replace('/\s+/', ' ', $text ?? '')));
    }

    protected function normalizeRow(array $row): array
    {
        $out = [];

        foreach ($row as $key => $value) {
            $key = strtolower(trim(str_replace(' ', '_', $key)));

            if ($key === 'movie_titl') $key = 'movie_title';
            if ($key === 'event_titl') $key = 'event_title';

            if (is_numeric($value) && $key === 'show_date') {
                $out[$key] = ExcelDate::excelToDateTimeObject($value)->format('Y-m-d');
                continue;
            }

            if (is_numeric($value) && $key === 'start_time') {
                $out[$key] = ExcelDate::excelToDateTimeObject($value)->format('H:i');
                continue;
            }

            $out[$key] = is_string($value)
                ? trim(preg_replace('/\s+/', ' ', $value))
                : $value;
        }

        return $out;
    }

    protected function validateRow(array $data): void
    {
        $validator = Validator::make($data, [
            'screen_id'   => ['required', 'exists:screens,id'],
            'show_date'   => ['required', 'date'],
            'start_time'  => ['required', 'date_format:H:i'],
            'movie_title' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    public function getSummary(): array
    {
        return [
            'created' => $this->created,
            'skipped' => $this->skipped,
            'failed'  => $this->failed,
        ];
    }
}
