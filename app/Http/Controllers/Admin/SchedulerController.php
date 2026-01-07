<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Scheduler;
use App\Services\SchedulerImportService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SchedulerController extends Controller
{
    protected SchedulerImportService $service;

    public function __construct(SchedulerImportService $service)
    {
        $this->service = $service;
    }

 

    public function index()
    {
        $schedulers = Scheduler::with(['venue', 'screen'])
            ->orderBy('show_date')
            ->orderBy('start_time')
            ->get();

        return view('admin.schedulers.index', compact('schedulers'));
    }



    public function create()
    {
        return view('admin.schedulers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'venue_name'  => 'nullable|string',
            'screen_name' => 'required|string',

            'movie_title' => 'required_without:event_title|string',
            'event_title' => 'nullable|string',

            // Not trusted — service decides
            'language'    => 'nullable|string',
            'duration'    => 'nullable|integer|min:1',

            'show_date'   => 'required|date',
            'start_time'  => 'required|date_format:H:i',
        ]);

        try {
            $this->service->create($request->all());

           
            return redirect()->route('admin.schedulers.index');

        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput();
        }
    }

   

    public function edit(Scheduler $scheduler)
    {
        return view('admin.schedulers.edit', compact('scheduler'));
    }

    public function update(Request $request, Scheduler $scheduler)
    {
        $request->validate([
            'venue_name'  => 'nullable|string',
            'screen_name' => 'required|string',

            'movie_title' => 'required_without:event_title|string',
            'event_title' => 'nullable|string',

            'language'    => 'nullable|string',
            'duration'    => 'nullable|integer|min:1',

            'show_date'   => 'required|date',
            'start_time'  => 'required|date_format:H:i',
            'is_active'   => 'nullable|boolean',
        ]);

        try {
            $this->service->update($scheduler, $request->all());

            return redirect()->route('admin.schedulers.index');

        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput();
        }
    }



    public function destroy(Scheduler $scheduler)
    {
        if ($scheduler->is_active) {
            return back()->withErrors([
                'scheduler' => 'Active schedules cannot be deleted.',
            ]);
        }

        $scheduler->delete();

        return redirect()->route('admin.schedulers.index');
    }
}
