<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaffScreenAssignment;
use App\Models\User;
use App\Models\Venue;
use App\Models\Screen;
use Illuminate\Http\Request;

class StaffScreenAssignmentController extends Controller
{
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

    public function create()
    {
        $staff = User::role('staff')->orderBy('name')->get(['id', 'name']);
        $venues = Venue::with('screens:id,venue_id,name')
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin.staff-assignments.create', compact('staff', 'venues'));
    }

    /**
     * STORE — inline error on CREATE page
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

        // Business rule: only one active assignment per staff
        $alreadyAssigned = StaffScreenAssignment::where('user_id', $validated['user_id'])
            ->where('active', true)
            ->exists();

        if ($alreadyAssigned) {
            return back()
                ->withInput()
                ->withErrors([
                    'user_id' => 'This staff member is already assigned to another screen.',
                ]);
        }

        StaffScreenAssignment::create([
            'user_id'   => $validated['user_id'],
            'venue_id'  => $validated['venue_id'],
            'screen_id' => $validated['screen_id'],
            'active'    => true,
        ]);

        return redirect()->route('admin.staff-assignments.index');
    }

    
}
