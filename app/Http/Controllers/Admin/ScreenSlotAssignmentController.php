<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScreenSlotAssignment;
use App\Models\Venue;
use App\Models\Slot;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $taken  = ScreenSlotAssignment::all();

        return view('admin.ssa.create', compact('venues', 'movies', 'slots', 'taken'));
    }

    /**
     * Store new assignment (always inactive initially)
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
                Rule::unique('screen_slot_assignments')
                    ->where(fn ($q) =>
                        $q->where('screen_id', $request->screen_id)
                          ->where('day', $request->day)
                    ),
            ],
        ]);

        ScreenSlotAssignment::create($validated + [
            'status' => 'inactive',
        ]);

        return redirect()
            ->route('admin.ssa.index')
            ->with('success', 'Show assigned successfully.');
    }

    /**
     * View assignment
     */
    public function show($id)
    {
        $ssa = ScreenSlotAssignment::with(['venue', 'screen', 'slot', 'movie'])
            ->findOrFail($id);

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
        $taken  = ScreenSlotAssignment::where('id', '!=', $ssa->id)->get();

        return view('admin.ssa.edit', compact('ssa', 'venues', 'movies', 'slots', 'taken'));
    }

    /**
     * Update assignment (does NOT auto-activate)
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
                Rule::unique('screen_slot_assignments')
                    ->ignore($ssa->id)
                    ->where(fn ($q) =>
                        $q->where('screen_id', $request->screen_id)
                          ->where('day', $request->day)
                    ),
            ],
        ]);

        $ssa->update($validated);

        return redirect()
            ->route('admin.ssa.index')
            ->with('success', 'Show updated successfully.');
    }

    /**
     * Delete assignment
     */
    public function destroy(ScreenSlotAssignment $ssa)
    {
        $ssa->delete();

        return redirect()
            ->route('admin.ssa.index')
            ->with('success', 'Show deleted successfully.');
    }

    /**
     * Toggle ACTIVE / INACTIVE (AJAX)
     * Ensures only ONE active SSA per screen
     */
    public function toggleStatus(Request $request, ScreenSlotAssignment $ssa)
    {
        $request->validate([
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        DB::transaction(function () use ($ssa, $request) {

            if ($request->status === 'active') {
                // Deactivate all OTHER SSAs for this screen
                ScreenSlotAssignment::where('screen_id', $ssa->screen_id)
                    ->where('id', '!=', $ssa->id)
                    ->update(['status' => 'inactive']);
            }

            $ssa->update([
                'status' => $request->status,
            ]);
        });

        return response()->json([
            'success' => true,
            'status'  => $request->status,
        ]);
    }
}
