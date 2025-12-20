<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
    /**
     * Display all movies.
     */
    public function index()
    {
        $movies = Movie::orderBy('title')->get();

        return view('admin.movies.index', compact('movies'));
    }

    /**
     * Show the create form.
     */
    public function create()
    {
        $languages = ['Kannada', 'Hindi', 'English', 'Tamil', 'Telugu', 'Malayalam'];

        return view('admin.movies.create', compact('languages'));
    }

    /**
     * Store a new movie using ID-based logic.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'language'    => ['required', 'string'],
            'duration'    => ['nullable', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
            'status'      => ['required', 'in:active,inactive'],
            'poster'      => ['nullable', 'image', 'max:2048'], // 2MB
        ]);

        $posterPath = null;

        if ($request->hasFile('poster')) {
            $posterPath = $request->file('poster')->store('movies', 'public');
        }

        Movie::create([
            'title'       => $request->title,
            'language'    => $request->language,
            'duration'    => $request->duration,
            'description' => $request->description,
            'status'      => $request->status,
            'poster'      => $posterPath,
        ]);

        return redirect()->route('admin.movies.index')
            ->with('success', 'Movie created successfully.');
    }
    public function show($id)
    {
        $movie = Movie::findOrFail($id);

        return view('admin.movies.show', compact('movie'));
    }

    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $movie = Movie::findOrFail($id);
        $languages = ['Kannada', 'Hindi', 'English', 'Tamil', 'Telugu', 'Malayalam'];

        return view('admin.movies.edit', compact('movie', 'languages'));
    }

    /**
     * Update movie using ID.
     */
    public function update(Request $request, $id)
    {
        $movie = Movie::findOrFail($id);

        $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'language'    => ['required', 'string'],
            'duration'    => ['nullable', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
            'status'      => ['required', 'in:active,inactive'],
            'poster'      => ['nullable', 'image', 'max:2048'],
        ]);

        $posterPath = $movie->poster;

        if ($request->hasFile('poster')) {

            // Delete existing poster safely
            if ($posterPath && Storage::disk('public')->exists($posterPath)) {
                Storage::disk('public')->delete($posterPath);
            }

            $posterPath = $request->file('poster')->store('movies', 'public');
        }

        $movie->update([
            'title'       => $request->title,
            'language'    => $request->language,
            'duration'    => $request->duration,
            'description' => $request->description,
            'status'      => $request->status,
            'poster'      => $posterPath,
        ]);

        return redirect()->route('admin.movies.index')
            ->with('success', 'Movie updated successfully.');
    }

    /**
     * Delete a movie and remove file safely.
     */
    public function destroy($id)
    {
        $movie = Movie::findOrFail($id);

        if ($movie->poster && Storage::disk('public')->exists($movie->poster)) {
            Storage::disk('public')->delete($movie->poster);
        }

        $movie->delete();

        return redirect()->route('admin.movies.index')
            ->with('success', 'Movie deleted successfully.');
    }

    /**
     * AJAX: Toggle status (active / inactive).
     */
    public function toggleStatus(Request $request)
    {
        $request->validate([
            'id'     => 'required|exists:movies,id',
            'status' => 'required|in:active,inactive',
        ]);

        $movie = Movie::findOrFail($request->id);
        $movie->status = $request->status;
        $movie->save();

        return response()->json([
            'success' => true,
            'status'  => $movie->status
        ]);
    }
}
