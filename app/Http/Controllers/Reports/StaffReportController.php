<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\ScanLog;
use App\Models\Scheduler;
use App\Services\SlotResolver;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StaffScanLogsExport;

class StaffReportController extends Controller
{
    /**
     * Initial page load
     */
    public function index(Request $request)
    {
        $staff = auth()->user();
        abort_unless($staff, 401);

        $screen = $staff->screens()->wherePivot('active', true)->first();
        abort_unless($screen, 403, 'Screen not assigned');

        return view('reports.staff.index', [
            'dates'  => Scheduler::where('screen_id', $screen->id)
                ->distinct()
                ->orderBy('show_date')
                ->pluck('show_date'),
            'movies' => Scheduler::where('screen_id', $screen->id)
                ->distinct()
                ->orderBy('movie_title')
                ->pluck('movie_title'),
        ]);
    }

    /**
     * AJAX filter endpoint
     */
    public function ajaxFilter(Request $request)
    {
        $staff = auth()->user();
        abort_unless($staff, 401);

        $screen = $staff->screens()->wherePivot('active', true)->first();
        abort_unless($screen, 403);

        $query = ScanLog::query()
            ->with(['delegate', 'scheduler'])
            ->whereHas('scheduler', function ($q) use ($screen) {
                $q->where('screen_id', $screen->id);
            });

        // Date filter
        if ($request->filled('show_date')) {
            $query->whereHas('scheduler', function ($q) use ($request) {
                $q->whereDate('show_date', $request->show_date);
            });
        }

        // Movie filter
        if ($request->filled('movie_title')) {
            $query->whereHas('scheduler', function ($q) use ($request) {
                $q->where('movie_title', $request->movie_title);
            });
        }

        // Slot filter (derived)
        if ($request->filled('slot_no')) {
            $query->get()->filter(function ($log) use ($request) {
                return SlotResolver::slotNoForScheduler($log->scheduler) == $request->slot_no;
            });
        }

        $logs = $query
            ->orderByDesc('scanned_at')
            ->limit(2000)
            ->get()
            ->map(function ($log) {
                $log->slot_no = SlotResolver::slotNoForScheduler($log->scheduler);
                return $log;
            })
            ->values();

        return response()->json([
            'logs'  => $logs,
            'slots' => $request->filled('show_date')
                ? SlotResolver::forScreen(
                    $screen->id,
                    $request->show_date
                )
                : [],
        ]);
    }

    /**
     * Excel export (same filters)
     */
    public function exportExcel(Request $request)
    {
        $staff = auth()->user();
        abort_unless($staff, 401);

        $screen = $staff->screens()->wherePivot('active', true)->first();
        abort_unless($screen, 403);

        $query = ScanLog::query()
            ->with(['delegate', 'scheduler'])
            ->whereHas('scheduler', function ($q) use ($screen) {
                $q->where('screen_id', $screen->id);
            });

        // Apply SAME filters as AJAX
        if ($request->filled('show_date')) {
            $query->whereHas('scheduler', function ($q) use ($request) {
                $q->whereDate('show_date', $request->show_date);
            });
        }

        if ($request->filled('movie_title')) {
            $query->whereHas('scheduler', function ($q) use ($request) {
                $q->where('movie_title', $request->movie_title);
            });
        }

        if ($request->filled('slot_no')) {
            $query = $query->get()->filter(function ($log) use ($request) {
                return \App\Services\SlotResolver::slotNoForScheduler($log->scheduler)
                    == $request->slot_no;
            });

            // Convert back to query-safe collection export
            return Excel::download(
                new StaffScanLogsExport($query),
                'staff_scan_reports.xlsx'
            );
        }

        return Excel::download(
            new StaffScanLogsExport($query),
            'staff_scan_reports.xlsx'
        );
    }
}
