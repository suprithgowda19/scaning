<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slot;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SlotController extends Controller
{
    /**
     * List all global slots.
     */
    public function index()
    {
        $slots = Slot::orderBy('start_time')->get();

        return view('admin.slots.index', compact('slots'));
    }

    /**
     * Show create slot form.
     */
    public function create()
    {
        return view('admin.slots.create');
    }

    /**
     * Store a new global slot.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'start_time' => ['required', 'date_format:H:i'],
            'end_time'   => ['required', 'date_format:H:i', 'after:start_time'],

            // prevent duplicate time windows
            Rule::unique('slots')->where(function ($q) use ($request) {
                return $q->where('start_time', $request->start_time)
                         ->where('end_time', $request->end_time);
            }),
        ]);

        Slot::create($validated);

        return redirect()
            ->route('admin.slots.index')
            ->with('success', 'Slot created successfully.');
    }

    /**
     * Show edit slot form.
     */
    public function edit(Slot $slot)
    {
        return view('admin.slots.edit', compact('slot'));
    }

    /**
     * Update slot time window.
     */
    public function update(Request $request, Slot $slot)
    {
        $validated = $request->validate([
            'start_time' => ['required', 'date_format:H:i'],
            'end_time'   => ['required', 'date_format:H:i', 'after:start_time'],

            Rule::unique('slots')
                ->ignore($slot->id)
                ->where(function ($q) use ($request) {
                    return $q->where('start_time', $request->start_time)
                             ->where('end_time', $request->end_time);
                }),
        ]);

        $slot->update($validated);

        return redirect()
            ->route('admin.slots.index')
            ->with('success', 'Slot updated successfully.');
    }

    /**
     * Deleting slots is NOT allowed.
     */
    public function destroy()
    {
        abort(403, 'Deleting slots is not allowed.');
    }
}
