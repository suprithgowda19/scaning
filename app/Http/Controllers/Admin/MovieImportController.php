<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class MovieImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:10240',
        ]);

        $file = $request->file('file');
        $ext  = strtolower($file->getClientOriginalExtension());

        // Extract rows depending on file type
        if (in_array($ext, ['csv', 'txt'])) {
            $rows = array_map('str_getcsv', file($file->getRealPath()));
        } else {
            // Excel → convert to array
            $rows = Excel::toArray([], $file)[0] ?? [];
        }

        if (count($rows) < 2) {
            return back()->withErrors('Uploaded file is empty');
        }

        // Normalize header row
        $header = array_map(
            fn ($h) => strtolower(trim($h)),
            array_shift($rows)
        );

        // Required columns (strict)
        $required = ['original_title', 'language', 'duration'];

        foreach ($required as $column) {
            if (! in_array($column, $header, true)) {
                return back()->withErrors("Missing required column: {$column}");
            }
        }

        // Map column → index
        $index = array_flip($header);

        DB::transaction(function () use ($rows, $index) {
            foreach ($rows as $row) {
                Movie::create([
                    'original_title' => $this->clean($row[$index['original_title']] ?? null),
                    'language'       => $this->clean($row[$index['language']] ?? null),
                    'duration'       => $this->toInt($row[$index['duration']] ?? null),
                ]);
            }
        });

        return back()->with('success', 'Movies imported successfully');
    }

    /* -----------------------------
       Helpers (kept private)
    ----------------------------- */

    private function clean($value): ?string
    {
        $value = trim((string) $value);

        return ($value === '' || strtoupper($value) === 'N/A')
            ? null
            : $value;
    }

    private function toInt($value): ?int
    {
        return is_numeric($value) ? (int) $value : null;
    }
}
