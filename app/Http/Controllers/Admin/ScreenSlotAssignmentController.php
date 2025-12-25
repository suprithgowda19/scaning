<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScreenSlotAssignment;
use App\Models\Screen;
use App\Models\Slot;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ScreenSlotAssignmentController extends Controller
{
    /**
     * List all shows.
     */
    public function index()
    {
        $shows = ScreenSlotAssignment::with([
                'screen.venue',
                'slot',
                'movie',
            ])
            ->orderBy('show_date')
            ->orderBy('slot_id')
            ->orderBy('screen_id')
            ->paginate(50);

        return view('admin.ssa.index', compact('shows'));
    }

    /**
     * View single show.
     */
    public function show(ScreenSlotAssignment $ssa)
    {
        $ssa->load(['screen.venue', 'slot', 'movie']);

        return view('admin.ssa.show', compact('ssa'));
    }

    /**
     * Edit show (swap / move).
     */
    public function edit(ScreenSlotAssignment $ssa)
    {
        $ssa->load(['screen.venue', 'slot', 'movie']);

        $movies  = Movie::orderBy('title')->get();
        $screens = Screen::where('venue_id', $ssa->screen->venue_id)
            ->orderBy('name')
            ->get();

        return view('admin.ssa.edit', compact('ssa', 'movies', 'screens'));
    }

    /**
     * Update show.
     * Allows swapping movie or moving screen.
     */
    public function update(Request $request, ScreenSlotAssignment $ssa)
    {
        $request->validate([
            'movie_id'  => ['required', 'exists:movies,id'],
            'screen_id' => [
                'required',
                'exists:screens,id',

                // prevent collision: same screen + slot + date
                Rule::unique('screen_slot_assignments')
                    ->ignore($ssa->id)
                    ->where(fn ($q) =>
                        $q->where('slot_id', $ssa->slot_id)
                          ->where('show_date', $ssa->show_date)
                    ),
            ],
        ]);

        DB::transaction(function () use ($ssa, $request) {
            $ssa->update([
                'movie_id'  => $request->movie_id,
                'screen_id' => $request->screen_id,
            ]);
        });

        return redirect()
            ->route('admin.ssa.index')
            ->with('success', 'Show updated successfully.');
    }

    /**
     * Deleting shows is NOT allowed.
     */
    public function destroy()
    {
        abort(403, 'Deleting shows is not allowed.');
    }
}
