<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slot;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SlotController extends Controller
{
    // List Slots
    public function index()
    {
        $slots = Slot::with('venue')
            ->orderBy('venue_id')
            ->orderBy('start_time')
            ->get(); // Using DataTables, no pagination needed

        return view('admin.slots.index', compact('slots'));
    }

    // Create Page
    public function create()
    {
        $venues = Venue::orderBy('name')->get();
        return view('admin.slots.create', compact('venues'));
    }

    // Store Slot
    public function store(Request $request)
    {
        $validated = $request->validate([
            'venue_id'   => ['required', 'exists:venues,id'],
            'start_time' => [
                'required',
                'date_format:H:i',

                // Prevent duplicates for same venue
                Rule::unique('slots')
                    ->where('venue_id', $request->venue_id),
            ],
        ]);

        Slot::create($validated);

        return redirect()
            ->route('admin.slots.index')
            ->with('success', 'Slot created successfully.');
    }

    // Edit Page
    public function edit(Slot $slot)
    {
        $venues = Venue::orderBy('name')->get();
        return view('admin.slots.edit', compact('slot', 'venues'));
    }

    // Update Slot
    public function update(Request $request, Slot $slot)
    {
        $validated = $request->validate([
            'venue_id'   => ['required', 'exists:venues,id'],
            'start_time' => [
                'required',
                'date_format:H:i',

                // Allow same value for current slot, block duplicates
                Rule::unique('slots')
                    ->ignore($slot->id)
                    ->where('venue_id', $request->venue_id),
            ],
        ]);

        $slot->update($validated);

        return redirect()
            ->route('admin.slots.index')
            ->with('success', 'Slot updated successfully.');
    }

    // Delete Slot
    public function destroy(Slot $slot)
    {
        $slot->delete();

        return redirect()
            ->route('admin.slots.index')
            ->with('success', 'Slot deleted successfully.');
    }
}
