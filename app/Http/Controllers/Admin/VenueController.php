<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use Illuminate\Http\Request;

class VenueController extends Controller
{
    /**
     * Display a listing of venues.
     * Frontend (DataTables) handles search, sorting, pagination.
     */
    public function index()
    {
        $venues = Venue::orderBy('name')->get();

        return view('admin.venues.index', compact('venues'));
    }

    /**
     * Show the form for creating a new venue.
     */
    public function create()
    {
        return view('admin.venues.create');
    }

    /**
     * Store a newly created venue.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'    => ['required', 'string', 'max:255', 'unique:venues,name'],
            'address' => ['nullable', 'string'],
         
        ]);

        Venue::create([
            'name'    => $request->name,
            'address' => $request->address,
            'active'  => $request->boolean('active'),
        ]);

        return redirect()
            ->route('admin.venues.index')
            ->with('success', 'Venue created successfully.');
    }

    /**
     * Show the form for editing a venue.
     */
    public function edit($id)
    {
        $venue = Venue::findOrFail($id);

        return view('admin.venues.edit', compact('venue'));
    }

    /**
     * Update a venue.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'    => ['required', 'string', 'max:255', 'unique:venues,name,' . $id],
            'address' => ['nullable', 'string'],
         
        ]);

        $venue = Venue::findOrFail($id);

        $venue->update([
            'name'    => $request->name,
            'address' => $request->address,
         
        ]);

        return redirect()
            ->route('admin.venues.index')
            ->with('success', 'Venue updated successfully.');
    }

    /**
     * Delete a venue.
     */
    public function destroy($id)
    {
        $venue = Venue::findOrFail($id);
        $venue->delete();

        return redirect()
            ->route('admin.venues.index')
            ->with('success', 'Venue deleted successfully.');
    }
}
