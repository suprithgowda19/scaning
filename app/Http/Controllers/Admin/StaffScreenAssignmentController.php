<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaffScreenAssignment;
use App\Models\User;
use App\Models\Venue;
use App\Models\Screen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffScreenAssignmentController extends Controller
{
    /**
     * List assignments
     */
    public function index()
    {
        $assignments = StaffScreenAssignment::with([
                'user:id,name',
                'venue:id,name',
                'screen:id,name',
            ])
            ->latest()
            ->paginate(20);

        return view('admin.staff-assignments.index', compact('assignments'));
    }

    /**
     * Create form
     */
    public function create()
    {
        $staff = User::role('staff')
            ->orderBy('name')
            ->get(['id', 'name']);

        $venues = Venue::with('screens:id,venue_id,name')
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin.staff-assignments.create', compact('staff', 'venues'));
    }

    /**
     * Store assignment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'   => 'required|exists:users,id',
            'venue_id'  => 'required|exists:venues,id',
            'screen_id' => 'required|exists:screens,id',
        ]);

        // Ensure staff role
        User::role('staff')->findOrFail($validated['user_id']);

        // Ensure screen belongs to venue
        Screen::where('id', $validated['screen_id'])
            ->where('venue_id', $validated['venue_id'])
            ->firstOrFail();

        DB::transaction(function () use ($validated) {

            // Deactivate existing assignment (REASSIGNMENT)
            StaffScreenAssignment::where('user_id', $validated['user_id'])
                ->update(['active' => false]);

            StaffScreenAssignment::create([
                'user_id'   => $validated['user_id'],
                'venue_id'  => $validated['venue_id'],
                'screen_id' => $validated['screen_id'],
                'active'    => true,
            ]);
        });

        return redirect()
            ->route('admin.staff-assignments.index')
            ->with('success', 'Staff assigned successfully.');
    }

    /**
     * Edit form
     */
    public function edit(string $id)
    {
        $assignment = StaffScreenAssignment::with([
            'user:id,name',
            'venue:id,name',
            'screen:id,name',
        ])->findOrFail($id);

        $staff = User::role('staff')->orderBy('name')->get(['id', 'name']);
        $venues = Venue::with('screens:id,venue_id,name')
            ->orderBy('name')
            ->get(['id', 'name']);

        return view(
            'admin.staff-assignments.edit',
            compact('assignment', 'staff', 'venues')
        );
    }

    /**
     * Update assignment
     */
    public function update(Request $request, string $id)
    {
        $assignment = StaffScreenAssignment::findOrFail($id);

        /**
         * AJAX toggle
         */
        if ($request->expectsJson()) {
            $request->validate(['active' => 'required|boolean']);

            DB::transaction(function () use ($assignment, $request) {

                if ($request->active) {
                    // Deactivate other screens for this staff
                    StaffScreenAssignment::where('user_id', $assignment->user_id)
                        ->where('id', '!=', $assignment->id)
                        ->update(['active' => false]);
                }

                $assignment->update(['active' => $request->active]);
            });

            return response()->json(['success' => true]);
        }

        /**
         * Full edit
         */
        $validated = $request->validate([
            'user_id'   => 'required|exists:users,id',
            'venue_id'  => 'required|exists:venues,id',
            'screen_id' => 'required|exists:screens,id',
            'active'    => 'required|boolean',
        ]);

        User::role('staff')->findOrFail($validated['user_id']);

        Screen::where('id', $validated['screen_id'])
            ->where('venue_id', $validated['venue_id'])
            ->firstOrFail();

        DB::transaction(function () use ($assignment, $validated) {

            if ($validated['active']) {
                StaffScreenAssignment::where('user_id', $validated['user_id'])
                    ->where('id', '!=', $assignment->id)
                    ->update(['active' => false]);
            }

            $assignment->update($validated);
        });

        return redirect()
            ->route('admin.staff-assignments.index')
            ->with('success', 'Assignment updated successfully.');
    }

    /**
     * Revoke assignment
     */
    public function destroy(string $id)
    {
        StaffScreenAssignment::findOrFail($id)
            ->update(['active' => false]);

        return redirect()
            ->route('admin.staff-assignments.index')
            ->with('success', 'Assignment revoked.');
    }
}
