<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class MovieController extends Controller
{
 
    public function index()
    {
        $movies = Movie::orderByDesc('created_at')->get();
        return view('admin.movies.index', compact('movies'));
    }

    public function create()
    {
        return view('admin.movies.create');
    }

 
    public function store(Request $request)
    {
        $request->validate([
            'original_title' => 'required|string|max:255',
            'eng_title'      => 'nullable|string|max:255',
            'language'       => 'nullable|string|max:255',
            'duration'       => 'nullable|integer|min:1',
        ]);

        Movie::create($request->only([
            'original_title',
            'eng_title',
            'language',
            'duration',
        ]));

        return redirect()
            ->route('admin.movies.index')
            ->with('success', 'Movie created successfully');
    }


    public function edit(Movie $movie)
    {
        return view('admin.movies.edit', compact('movie'));
    }

    public function update(Request $request, Movie $movie)
    {
        $request->validate([
            'original_title' => 'required|string|max:255',
            'eng_title'      => 'nullable|string|max:255',
            'language'       => 'nullable|string|max:255',
            'duration'       => 'nullable|integer|min:1',
        ]);

        $movie->update($request->only([
            'original_title',
            'eng_title',
            'language',
            'duration',
        ]));

        return redirect()
            ->route('admin.movies.index')
            ->with('success', 'Movie updated successfully');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:10240',
        ]);

        $file = $request->file('file');
        $ext  = strtolower($file->getClientOriginalExtension());

        // Read rows
        if (in_array($ext, ['csv', 'txt'])) {
            $rows = array_map('str_getcsv', file($file->getRealPath()));
        } else {
            $rows = Excel::toArray([], $file)[0] ?? [];
        }

        if (count($rows) < 2) {
            return back()->withErrors('Uploaded file is empty');
        }

        // Normalize header
        $header = array_map(
            fn ($h) => strtolower(trim(str_replace(' ', '_', $h))),
            array_shift($rows)
        );

        // Required columns
        $required = ['original_title', 'eng_title', 'language', 'duration'];

        foreach ($required as $column) {
            if (! in_array($column, $header, true)) {
                return back()->withErrors("Missing required column: {$column}");
            }
        }

        $index = array_flip($header);

        DB::transaction(function () use ($rows, $index) {
            foreach ($rows as $row) {

                $originalTitle = $this->clean($row[$index['original_title']] ?? null);
                $duration      = $this->toInt($row[$index['duration']] ?? null);

                // Skip invalid rows
                if (! $originalTitle || ! $duration) {
                    continue;
                }

                $data = [
                    'language' => $this->clean($row[$index['language']] ?? null),
                ];

                // Only set eng_title if value exists (do NOT overwrite with null)
                $engTitle = $this->clean($row[$index['eng_title']] ?? null);
                if ($engTitle !== null) {
                    $data['eng_title'] = $engTitle;
                }

                Movie::updateOrCreate(
                    [
                        // Uniqueness rule
                        'original_title' => $originalTitle,
                        'duration'       => $duration,
                    ],
                    $data
                );
            }
        });

        return redirect()
            ->route('admin.movies.index')
            ->with('success', 'Movies imported / updated successfully');
    }
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
