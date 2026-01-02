<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Screen;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ScreenController extends Controller
{
    public function index()
    {
        $screens = Screen::with('venue')
            ->orderBy('venue_id')
            ->orderBy('name')
            ->get();

        return view('admin.screens.index', compact('screens'));
    }

    public function create()
    {
        $venues = Venue::orderBy('name')->get();
        return view('admin.screens.create', compact('venues'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'venue_id' => ['required', 'exists:venues,id'],
            'name'     => [
                'required',
                'string',
                'max:255',
                Rule::unique('screens')
                    ->where(fn ($q) => $q->where('venue_id', $request->venue_id)),
            ],
            'capacity' => ['required', 'integer', 'min:1'],
        ]);

        Screen::create($validated);

        return redirect()
            ->route('admin.screens.index')
            ->with('success', 'Screen created successfully.');
    }

    public function edit(Screen $screen)
    {
        $venues = Venue::orderBy('name')->get();
        return view('admin.screens.edit', compact('screen', 'venues'));
    }

    public function update(Request $request, Screen $screen)
    {
        $validated = $request->validate([
            'venue_id' => ['required', 'exists:venues,id'],
            'name'     => [
                'required',
                'string',
                'max:255',
                Rule::unique('screens')
                    ->ignore($screen->id)
                    ->where(fn ($q) => $q->where('venue_id', $request->venue_id)),
            ],
            'capacity' => ['required', 'integer', 'min:1'],
        ]);

        $screen->update($validated);

        return redirect()
            ->route('admin.screens.index')
            ->with('success', 'Screen updated successfully.');
    }

    public function destroy(Screen $screen)
    {
        // Safety guard
        if ($screen->schedules()->exists()) {
            return redirect()
                ->route('admin.screens.index')
                ->with('error', 'Cannot delete screen with scheduled events.');
        }

        if ($screen->scanLogs()->exists()) {
            return redirect()
                ->route('admin.screens.index')
                ->with('error', 'Cannot delete screen with scan history.');
        }

        $screen->delete();

        return redirect()
            ->route('admin.screens.index')
            ->with('success', 'Screen deleted successfully.');
    }
}
