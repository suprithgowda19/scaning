<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VenueController extends Controller
{
   
    public function index()
    {
        $venues = Venue::orderBy('name')->get();

        return view('admin.venues.index', compact('venues'));
    }

 
    public function create()
    {
        return view('admin.venues.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:venues,name',
            ],
        ]);

        Venue::create($validated);

        return redirect()
            ->route('admin.venues.index')
            ->with('success', 'Venue created successfully.');
    }

   
    public function show(Venue $venue)
    {
        return view('admin.venues.show', compact('venue'));
    }

    public function edit(Venue $venue)
    {
        return view('admin.venues.edit', compact('venue'));
    }

    public function update(Request $request, Venue $venue)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('venues', 'name')->ignore($venue->id),
            ],
        ]);

        $venue->update($validated);

        return redirect()
            ->route('admin.venues.index')
            ->with('success', 'Venue updated successfully.');
    }

    public function destroy(Venue $venue)
    {
       
        if ($venue->screens()->exists()) {
            return redirect()
                ->route('admin.venues.index')
                ->with('error', 'Cannot delete venue with screens. Remove screens first.');
        }

        $venue->delete();

        return redirect()
            ->route('admin.venues.index')
            ->with('success', 'Venue deleted successfully.');
    }
}
