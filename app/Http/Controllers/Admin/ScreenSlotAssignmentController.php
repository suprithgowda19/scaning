<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScreenSlotAssignment;
use App\Models\Venue;
use App\Models\Screen;
use App\Models\Slot;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ScreenSlotAssignmentController extends Controller
{
    /**
     * Display all assignments.
     */
    public function index()
    {
        $assignments = ScreenSlotAssignment::with(['venue', 'screen', 'slot', 'movie'])
            ->orderBy('venue_id')
            ->orderBy('screen_id')
            ->orderBy('day')
            ->orderBy('slot_id')
            ->get();

        return view('admin.ssa.index', compact('assignments'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        $venues = Venue::with('screens')->orderBy('name')->get();
        $movies = Movie::orderBy('title')->get();
        $slots  = Slot::orderBy('start_time')->get();
        $taken  = ScreenSlotAssignment::all(); // for Alpine filtering - available slots

        return view('admin.ssa.create', compact('venues', 'movies', 'slots', 'taken'));
    }

    /**
     * Store new assignment.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'venue_id'  => ['required', 'exists:venues,id'],
            'screen_id' => ['required', 'exists:screens,id'],
            'movie_id'  => ['required', 'exists:movies,id'],
            'day'       => ['required', 'integer', 'min:1', 'max:7'],

            'slot_id'   => [
                'required',
                'exists:slots,id',

                // Prevent double booking:
                // Screen cannot use same slot on same day twice.
                Rule::unique('screen_slot_assignments')
                    ->where(
                        fn($query) =>
                        $query->where('screen_id', request('screen_id'))
                            ->where('day', request('day'))
                    ),
            ],
        ]);

        ScreenSlotAssignment::create($validated);

        return redirect()
            ->route('admin.ssa.index')
            ->with('success', 'Show assigned successfully.');
    }
    public function show($id)
    {  
        $ssa = ScreenSlotAssignment::with(['venue', 'screen', 'slot', 'movie'])->findOrFail($id);
        return view('admin.ssa.show', compact('ssa'));
    }

    /**
     * Show edit form.
     */
    public function edit(ScreenSlotAssignment $ssa)
    {
        $venues = Venue::with('screens')->orderBy('name')->get();
        $movies = Movie::orderBy('title')->get();
        $slots  = Slot::orderBy('start_time')->get();

        // All other records except current for slot filtering
        $taken = ScreenSlotAssignment::where('id', '!=', $ssa->id)->get();

        return view('admin.ssa.edit', compact('ssa', 'venues', 'movies', 'slots', 'taken'));
    }

    /**
     * Update existing assignment.
     */
    public function update(Request $request, ScreenSlotAssignment $ssa)
    {
        $validated = $request->validate([
            'venue_id'  => ['required', 'exists:venues,id'],
            'screen_id' => ['required', 'exists:screens,id'],
            'movie_id'  => ['required', 'exists:movies,id'],
            'day'       => ['required', 'integer', 'min:1', 'max:7'],

            'slot_id'   => [
                'required',
                'exists:slots,id',

                // Prevent double booking, but ignore current SSA record
                Rule::unique('screen_slot_assignments')
                    ->ignore($ssa->id)
                    ->where(
                        fn($query) =>
                        $query->where('screen_id', request('screen_id'))
                            ->where('day', request('day'))
                    ),
            ],
        ]);

        $ssa->update($validated);

        return redirect()
            ->route('admin.ssa.index')
            ->with('success', 'Show updated successfully.');
    }

    /**
     * Delete assignment.
     */
    public function destroy(ScreenSlotAssignment $ssa)
    {
        $ssa->delete();

        return redirect()
            ->route('admin.ssa.index')
            ->with('success', 'Show deleted successfully.');
    }
}
