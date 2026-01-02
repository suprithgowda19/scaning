<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Scheduler;
use App\Services\SchedulerImportService;
use Illuminate\Http\Request;

class SchedulerController extends Controller
{
    protected SchedulerImportService $service;

    public function __construct(SchedulerImportService $service)
    {
        $this->service = $service;
    }

    /* ============================
     | INDEX
     ============================ */

    public function index()
    {
        $schedulers = Scheduler::with(['venue', 'screen'])
            ->orderBy('show_date')
            ->orderBy('start_time')
            ->paginate(20);

        return view('admin.schedulers.index', compact('schedulers'));
    }

    /* ============================
     | CREATE
     ============================ */

    public function create()
    {
        return view('admin.schedulers.create');
    }

    public function store(Request $request)
    {
        // Controller validation = syntax only
        $request->validate([
            'venue_name'  => 'nullable|string',
            'screen_name' => 'required|string',
            'movie_title' => 'nullable|string',
            'event_title' => 'nullable|string',
            'language'    => 'nullable|string',
            'duration'    => 'nullable|integer|min:1',
            'show_date'   => 'required|date',
            'start_time'  => 'required',
        ]);

        $this->service->create($request->all());

        return redirect()
            ->route('admin.schedulers.index')
            ->with('success', 'Schedule created successfully');
    }

    /* ============================
     | EDIT
     ============================ */

    public function edit(Scheduler $scheduler)
    {
        return view('admin.schedulers.edit', compact('scheduler'));
    }

    public function update(Request $request, Scheduler $scheduler)
    {
        $request->validate([
            'venue_name'  => 'nullable|string',
            'screen_name' => 'required|string',
            'movie_title' => 'nullable|string',
            'event_title' => 'nullable|string',
            'language'    => 'nullable|string',
            'duration'    => 'nullable|integer|min:1',
            'show_date'   => 'required|date',
            'start_time'  => 'required',
            'is_active'   => 'nullable|boolean',
        ]);

        $this->service->update($scheduler, $request->all());

        return redirect()
            ->route('admin.schedulers.index')
            ->with('success', 'Schedule updated successfully');
    }

    /* ============================
     | DELETE
     ============================ */

    public function destroy(Scheduler $scheduler)
    {
        $scheduler->delete();

        return redirect()
            ->route('admin.schedulers.index')
            ->with('success', 'Schedule deleted successfully');
    }
}
