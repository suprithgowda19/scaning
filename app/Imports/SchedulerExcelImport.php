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
    protected int $updated = 0;
    protected int $failed  = 0;

    /**
     * Map of Normalized Screen Name => Screen ID
     * Example: ['audi 1' => 5, 'gold class' => 8]
     */
    protected array $screenMap = [];

    public function __construct()
    {
        $this->service = app(SchedulerImportService::class);
        
        // 1️⃣ PRE-LOAD & NORMALIZE SCREENS
        // We load all screens once to handle case-insensitivity & spacing efficiently
        $this->loadScreenMap();
    }

    protected function loadScreenMap(): void
    {
        // Fetch ID and Name only
        $screens = Screen::all(['id', 'name']);

        foreach ($screens as $screen) {
            // Normalize DB Name: Lowercase, Trim, Single Spaces
            $cleanName = $this->cleanString($screen->name);
            $this->screenMap[$cleanName] = $screen->id;
        }
    }

    public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $row) {
            try {
                // 2️⃣ Normalize Excel Data
                $data = $this->normalizeRow($row->toArray());

                // 3️⃣ MAP SCREEN NAME -> SCREEN ID
                // Check if the normalized name exists in our pre-loaded map
                $cleanScreenName = $this->cleanString($data['screen_name'] ?? '');
                
                if (isset($this->screenMap[$cleanScreenName])) {
                    $data['screen_id'] = $this->screenMap[$cleanScreenName];
                } else {
                    // Throw specific error if screen doesn't exist
                    throw new \Exception("Screen '{$data['screen_name']}' not found in database.");
                }

                // 4️⃣ Validate (Now checking screen_id presence)
                $this->validateRow($data);

                // 5️⃣ Check for Existing Show (using screen_id)
                $existing = Scheduler::where('show_date', $data['show_date'])
                    ->where('start_time', $data['start_time'])
                    ->where('screen_id', $data['screen_id']) 
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
                Log::error('Scheduler Import Error', [
                    'row' => $index + 2,
                    'msg' => $e->getMessage(),
                    'data' => $row->toArray()
                ]);
            }
        }
    }

    /**
     * Standardizer for Name Comparison
     * "  Audi   1 " -> "audi 1"
     */
    protected function cleanString(?string $text): string
    {
        if (!$text) return '';
        // Lowercase -> Trim -> Replace multiple spaces with single space
        return strtolower(trim(preg_replace('/\s+/', ' ', $text)));
    }

    protected function normalizeRow(array $row): array
    {
        $normalized = [];

        foreach ($row as $key => $value) {
            $key = strtolower(trim($key));
            $key = str_replace(' ', '_', $key);

            // Column Mapping
            if ($key === 'movie_titl') $key = 'movie_title';
            if ($key === 'event_titl') $key = 'event_title';

            // Date/Time Parsing
            if (is_numeric($value)) {
                if ($key === 'show_date') {
                    $normalized[$key] = ExcelDate::excelToDateTimeObject($value)->format('Y-m-d');
                    continue;
                }
                if ($key === 'start_time') {
                    $normalized[$key] = ExcelDate::excelToDateTimeObject($value)->format('H:i');
                    continue;
                }
            }

            // String Cleanup
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

    protected function validateRow(array $data): void
    {
        $validator = Validator::make($data, [
            // Ensure ID was mapped successfully
            'screen_id'   => ['required', 'integer', 'exists:screens,id'], 
            
            'show_date'   => ['required', 'date'],
            'start_time'  => ['required', 'date_format:H:i'],
            'movie_title' => ['required_without:event_title', 'nullable', 'string'],
            'event_title' => ['required_without:movie_title', 'nullable', 'string'],
            'language'    => ['nullable', 'string'],
            'duration'    => ['nullable', 'integer', 'min:1'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    public function getSummary(): array
    {
        return [
            'created' => $this->created,
            'updated' => $this->updated,
            'failed'  => $this->failed,
        ];
    }
}