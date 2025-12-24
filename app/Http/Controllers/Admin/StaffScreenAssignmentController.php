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
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.staff-assignments.index', compact('assignments'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $staff = User::role('staff')
            ->orderBy('name')
            ->get(['id', 'name']);

        $venues = Venue::with('screens:id,venue_id,name,status')
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin.staff-assignments.create', compact('staff', 'venues'));
    }

    /**
     * Store assignment
     */
    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'user_id'   => 'required|exists:users,id',
                'venue_id'  => 'required|exists:venues,id',
                'screen_id' => 'required|exists:screens,id',
            ],
            [
                'user_id.required'   => 'Please select a staff member.',
                'venue_id.required'  => 'Please select a venue.',
                'screen_id.required' => 'Please select a screen.',
            ]
        );

        // Ensure user is STAFF
        User::role('staff')->findOrFail($validated['user_id']);

        // Ensure screen belongs to venue
        Screen::where('id', $validated['screen_id'])
            ->where('venue_id', $validated['venue_id'])
            ->firstOrFail();

        /**
         * BUSINESS RULE #1
         * Staff can have ONLY ONE active assignment
         */
        $alreadyAssigned = StaffScreenAssignment::where('user_id', $validated['user_id'])
            ->where('active', true)
            ->exists();

        if ($alreadyAssigned) {
            return back()
                ->withErrors([
                    'user_id' => 'This staff member is already assigned to another screen.',
                ])
                ->withInput();
        }

        /**
         * BUSINESS RULE #2
         * Prevent duplicate assignment (same staff + same screen)
         */
        $duplicate = StaffScreenAssignment::where([
            'user_id'   => $validated['user_id'],
            'screen_id' => $validated['screen_id'],
        ])->exists();

        if ($duplicate) {
            return back()
                ->withErrors([
                    'screen_id' => 'This staff member is already assigned to the selected screen.',
                ])
                ->withInput();
        }

        DB::transaction(function () use ($validated) {
            StaffScreenAssignment::create([
                'user_id'   => $validated['user_id'],
                'venue_id'  => $validated['venue_id'],
                'screen_id' => $validated['screen_id'],
                'active'    => true,
            ]);
        });

        return redirect()
            ->route('admin.staff-assignments.index')
            ->with('success', 'Staff assigned to screen successfully.');
    }

    /**
     * Show edit form
     */
    public function edit(string $id)
    {
        $assignment = StaffScreenAssignment::with([
            'user:id,name',
            'venue:id,name',
            'screen:id,name',
        ])->findOrFail($id);

        $staff = User::role('staff')->orderBy('name')->get(['id', 'name']);
        $venues = Venue::with('screens:id,venue_id,name,status')
            ->orderBy('name')
            ->get(['id', 'name']);

        return view(
            'admin.staff-assignments.edit',
            compact('assignment', 'staff', 'venues')
        );
    }

    /**
     * Update assignment
     * Handles:
     * - AJAX toggle (active/inactive)
     * - Full edit form submit
     */
    public function update(Request $request, string $id)
    {
        $assignment = StaffScreenAssignment::findOrFail($id);

        /**
         * AJAX toggle from index page
         */
        if ($request->expectsJson()) {
            $request->validate([
                'active' => 'required|boolean',
            ]);

            $assignment->update([
                'active' => $request->active,
            ]);

            return response()->json(['success' => true]);
        }

        $validated = $request->validate(
            [
                'user_id'   => 'required|exists:users,id',
                'venue_id'  => 'required|exists:venues,id',
                'screen_id' => 'required|exists:screens,id',
                'active'    => 'required|boolean',
            ],
            [
                'user_id.required'   => 'Please select a staff member.',
                'venue_id.required'  => 'Please select a venue.',
                'screen_id.required' => 'Please select a screen.',
            ]
        );

        User::role('staff')->findOrFail($validated['user_id']);

        Screen::where('id', $validated['screen_id'])
            ->where('venue_id', $validated['venue_id'])
            ->firstOrFail();

        /**
         * Prevent assigning same staff to multiple screens
         */
        $conflict = StaffScreenAssignment::where('user_id', $validated['user_id'])
            ->where('id', '!=', $assignment->id)
            ->where('active', true)
            ->exists();

        if ($conflict) {
            return back()
                ->withErrors([
                    'user_id' => 'This staff member is already assigned to another screen.',
                ])
                ->withInput();
        }

        DB::transaction(function () use ($assignment, $validated) {
            $assignment->update($validated);
        });

        return redirect()
            ->route('admin.staff-assignments.index')
            ->with('success', 'Assignment updated successfully.');
    }

    /**
     * Revoke assignment (soft)
     */
    public function destroy(string $id)
    {
        StaffScreenAssignment::findOrFail($id)
            ->update(['active' => false]);

        return redirect()
            ->route('admin.staff-assignments.index')
            ->with('success', 'Assignment revoked successfully.');
    }
}
