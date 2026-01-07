<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\ScanLog;
use App\Models\Scheduler;
use App\Services\SlotResolver;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StaffScanLogsExport;

class StaffReportController extends Controller
{
    /* =========================================================
     | INDEX
     ========================================================= */

    public function index()
    {
        $staff = auth()->user();
        abort_unless($staff, 401);

        $screen = $staff->screens()
            ->wherePivot('active', true)
            ->first();

        abort_unless($screen, 403, 'Screen not assigned');

        return view('reports.staff.index', [
            // Only dates are preloaded
            'dates' => Scheduler::where('screen_id', $screen->id)
                ->orderBy('show_date')
                ->pluck('show_date')
                ->unique()
                ->values(),

            // ❌ Movies & slots must be derived dynamically
            'movies' => [],
            'slots'  => [],
        ]);
    }

    /* =========================================================
     | AJAX FILTER
     ========================================================= */

    public function ajaxFilter(Request $request)
    {
        $staff = auth()->user();
        abort_unless($staff, 401);

        $screen = $staff->screens()
            ->wherePivot('active', true)
            ->first();

        abort_unless($screen, 403);

        /* ---------------- Normalize Inputs ---------------- */

        $showDate = $request->filled('show_date')
            ? Carbon::parse($request->show_date)->toDateString()
            : null;

        $movieTitle = $request->filled('movie_title')
            ? trim($request->movie_title)
            : null;

        $slotNo = $request->filled('slot_no')
            ? (int) $request->slot_no
            : null;

        /* ---------------- Base Query ---------------- */

        $query = ScanLog::query()
            ->with(['delegate', 'scheduler'])
            ->whereHas('scheduler', function ($q) use ($screen) {
                $q->where('screen_id', $screen->id);
            });

        /* ---------------- Date Filter ---------------- */

        if ($showDate) {
            $query->whereHas('scheduler', function ($q) use ($showDate) {
                $q->whereDate('show_date', $showDate);
            });
        }

        /* ---------------- Movie Filter ---------------- */

        if ($movieTitle) {
            $query->whereHas('scheduler', function ($q) use ($movieTitle) {
                $q->where('movie_title', $movieTitle);
            });
        }

        /* ---------------- Fetch Logs ---------------- */

        $logs = $query
            ->orderByDesc('scanned_at')
            ->limit(2000)
            ->get();

        /* ---------------- Slot Filter (Derived) ---------------- */

        if ($slotNo !== null) {
            $logs = $logs->filter(function ($log) use ($slotNo) {
                return SlotResolver::slotNoForScheduler($log->scheduler) === $slotNo;
            })->values();
        }

        /* ---------------- Attach Slot Numbers ---------------- */

        $logs->transform(function ($log) {
            $log->slot_no = SlotResolver::slotNoForScheduler($log->scheduler);
            return $log;
        });

        /* ---------------- Dynamic UI Data ---------------- */

        $movies = $showDate
            ? Scheduler::where('screen_id', $screen->id)
                ->whereDate('show_date', $showDate)
                ->orderBy('movie_title')
                ->pluck('movie_title')
                ->filter()
                ->unique()
                ->values()
            : [];

        $slots = $showDate
            ? SlotResolver::slotsForUI($showDate, $screen->id)
            : [];

        return response()->json([
            'logs'   => $logs,
            'movies' => $movies,
            'slots'  => $slots,
        ]);
    }

    /* =========================================================
     | EXCEL EXPORT (SAME FILTER LOGIC)
     ========================================================= */

    public function exportExcel(Request $request)
    {
        $staff = auth()->user();
        abort_unless($staff, 401);

        $screen = $staff->screens()
            ->wherePivot('active', true)
            ->first();

        abort_unless($screen, 403);

        $showDate = $request->filled('show_date')
            ? Carbon::parse($request->show_date)->toDateString()
            : null;

        $movieTitle = $request->filled('movie_title')
            ? trim($request->movie_title)
            : null;

        $slotNo = $request->filled('slot_no')
            ? (int) $request->slot_no
            : null;

        $query = ScanLog::query()
            ->with(['delegate', 'scheduler'])
            ->whereHas('scheduler', function ($q) use ($screen) {
                $q->where('screen_id', $screen->id);
            });

        if ($showDate) {
            $query->whereHas('scheduler', fn ($q) =>
                $q->whereDate('show_date', $showDate)
            );
        }

        if ($movieTitle) {
            $query->whereHas('scheduler', fn ($q) =>
                $q->where('movie_title', $movieTitle)
            );
        }

        $logs = $query
            ->orderByDesc('scanned_at')
            ->get();

        if ($slotNo !== null) {
            $logs = $logs->filter(function ($log) use ($slotNo) {
                return SlotResolver::slotNoForScheduler($log->scheduler) === $slotNo;
            })->values();
        }

        return Excel::download(
            new StaffScanLogsExport($logs),
            'staff_scan_reports.xlsx'
        );
    }
}
