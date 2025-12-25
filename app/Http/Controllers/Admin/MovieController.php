<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    /**
     * Display all movies (read-only list).
     */
    public function index()
    {
        $movies = Movie::orderBy('title')->paginate(50);

        return view('admin.movies.index', compact('movies'));
    }

    /**
     * Show single movie details.
     */
    public function show($id)
    {
        $movie = Movie::findOrFail($id);

        return view('admin.movies.show', compact('movie'));
    }

    /**
     * Show edit form (metadata correction only).
     */
    public function edit($id)
    {
        $movie = Movie::findOrFail($id);

        $languages = [
            'Kannada',
            'Hindi',
            'English',
            'Tamil',
            'Telugu',
            'Malayalam',
        ];

        return view('admin.movies.edit', compact('movie', 'languages'));
    }

    /**
     * Update movie metadata.
     * Scheduler identity (external_film_id) is NOT editable.
     */
    public function update(Request $request, $id)
    {
        $movie = Movie::findOrFail($id);

        $request->validate([
            'title'          => ['required', 'string', 'max:255'],
            'original_title' => ['nullable', 'string', 'max:255'],
            'language'       => ['nullable', 'string', 'max:100'],
            'duration'       => ['nullable', 'integer', 'min:1'],
            'country'        => ['nullable', 'string', 'max:100'],
            'year'           => ['nullable', 'string', 'max:10'],
            'director'       => ['nullable', 'string', 'max:255'],
            'category'       => ['nullable', 'string', 'max:100'],
        ]);

        $movie->update([
            'title'          => $request->title,
            'original_title' => $request->original_title,
            'language'       => $request->language,
            'duration'       => $request->duration,
            'country'        => $request->country,
            'year'           => $request->year,
            'director'       => $request->director,
            'category'       => $request->category,
        ]);

        return redirect()
            ->route('admin.movies.index')
            ->with('success', 'Movie updated successfully.');
    }

    /**
     * Deleting movies is NOT allowed.
     * Movies may already be referenced by shows (SSA).
     */
    public function destroy()
    {
        abort(403, 'Deleting movies is not allowed.');
    }
}
