<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Screen;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ScreenController extends Controller
{
    /**
     * Display list of screens.
     */
    public function index()
    {
        $screens = Screen::with('venue')->orderBy('venue_id')->orderBy('name')->get();

        return view('admin.screens.index', compact('screens'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        $venues = Venue::orderBy('name')->get();
        return view('admin.screens.create', compact('venues'));
    }

    /**
     * Store new screen.
     */
    public function store(Request $request)
    {
        $request->validate([
            'venue_id' => ['required', 'exists:venues,id'],
            'name'     => ['required', 'string', 'max:255'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'status'   => ['required', 'in:active,inactive'],
        ]);

        // Unique name check inside venue
        $exists = Screen::where('venue_id', $request->venue_id)
            ->where('name', $request->name)
            ->exists();

        if ($exists) {
            return back()->withErrors(['name' => 'Screen name already exists under this venue.'])
                ->withInput();
        }

        Screen::create($request->only('venue_id', 'name', 'capacity', 'status'));

        return redirect()->route('admin.screens.index')
            ->with('success', 'Screen created successfully.');
    }

    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $screen = Screen::findOrFail($id);
        $venues = Venue::orderBy('name')->get();

        return view('admin.screens.edit', compact('screen', 'venues'));
    }

    /**
     * Update screen.
     */
    public function update(Request $request, $id)
    {
        $screen = Screen::findOrFail($id);

        $request->validate([
            'venue_id' => ['required', 'exists:venues,id'],
            'name'     => ['required', 'string', 'max:255'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'status'   => ['required', 'in:active,inactive'],
        ]);

        // Unique screen name inside venue
        $exists = Screen::where('venue_id', $request->venue_id)
            ->where('name', $request->name)
            ->where('id', '!=', $screen->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['name' => 'Screen name already exists under this venue.'])
                ->withInput();
        }

        $screen->update($request->only('venue_id', 'name', 'capacity', 'status'));

        return redirect()->route('admin.screens.index')
            ->with('success', 'Screen updated successfully.');
    }

    /**
     * Delete a screen.
     */
    public function destroy($id)
    {
        $screen = Screen::findOrFail($id);
        $screen->delete();

        return redirect()->route('admin.screens.index')
            ->with('success', 'Screen deleted successfully.');
    }

    /**
     * AJAX status toggle.
     *
     * Expects JSON or form body: { id: <screen_id>, status: "active"|"inactive" }
     */
    public function toggleStatus(Request $request)
    {
        $request->validate([
            'id'     => ['required', 'exists:screens,id'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $screen = Screen::findOrFail($request->id);

        // Explicit assignment prevents accidental mass-assignment issues
        $screen->status = $request->status;
        $screen->save();

        return response()->json([
            'success' => true,
            'message' => 'Screen status updated.',
            'status'  => $screen->status,
            'id'      => $screen->id,
        ]);
    }
}
