<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\ScanLog;
use App\Models\Screen;
use App\Models\Scheduler;
use App\Services\SlotResolver;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AdminScanLogsExport;

class AdminReportController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()?->hasRole('admin'), 403);

        return view('reports.admin.index', [
            'screens' => Screen::orderBy('name')->get(['id', 'name']),
            'movies'  => Scheduler::distinct()->orderBy('movie_title')->pluck('movie_title'),
            'dates'   => Scheduler::distinct()->orderBy('show_date')->pluck('show_date'),

            // ❌ Do NOT preload slots here
            'slots'   => [],
        ]);
    }

    /**
     * AJAX FILTER
     */
    public function ajaxFilter(Request $request)
    {
        $query = ScanLog::with([
            'delegate:id,firstname,lastname,phone',
            'scheduler:id,screen_id,movie_title,show_date,start_time',
            'screen:id,name',
        ]);

        // ============================
        // Date filter
        // ============================
        if ($request->filled('show_date')) {
            $query->whereHas('scheduler', fn ($q) =>
                $q->whereDate('show_date', $request->show_date)
            );
        }

        // ============================
        // Screen filter
        // ============================
        if ($request->filled('screen_id')) {
            $query->whereHas('scheduler', fn ($q) =>
                $q->where('screen_id', $request->screen_id)
            );
        }

        // ============================
        // Movie filter
        // ============================
        if ($request->filled('movie_title')) {
            $query->whereHas('scheduler', fn ($q) =>
                $q->where('movie_title', $request->movie_title)
            );
        }

        // ============================
        // SLOT FILTER (DEPENDENT)
        // ============================
        if ($request->filled('slot_no')) {
            $schedulerIds = SlotResolver::schedulerIdsForSlot(
                $request->show_date,               // date FIRST
                (int) $request->slot_no,
                $request->screen_id
            );

            $schedulerIds
                ? $query->whereIn('scheduler_id', $schedulerIds)
                : $query->whereRaw('1=0');
        }

        // ============================
        // FETCH LOGS
        // ============================
        $logs = $query
            ->orderByDesc('scanned_at')
            ->limit(3000)
            ->get()
            ->map(function ($log) {
                $log->slot_no = SlotResolver::slotNoForScheduler($log->scheduler);
                return $log;
            })
            ->values();

        return response()->json([
            'logs'  => $logs,

            // ✅ Slots DEPEND on date + screen
            'slots' => SlotResolver::slotsForUI(
                $request->show_date,
                $request->screen_id
            ),
        ]);
    }

    public function exportExcel(Request $request)
    {
        abort_unless(auth()->user()?->hasRole('admin'), 403);

        return Excel::download(
            new AdminScanLogsExport($request),
            'admin_scan_reports.xlsx'
        );
    }
}
