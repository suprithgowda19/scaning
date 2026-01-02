<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ScanLog;
use App\Models\Slot;
use App\Models\Movie;
use App\Models\ScreenSlotAssignment;
use App\Exports\StaffScanLogsExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StaffDashboardController extends Controller
{
    /**
     * Staff Scan Reports (Table + Filters)
     */
    public function index(Request $request)
    {
        $staff = auth()->user();
        abort_unless($staff, 401);

        // Resolve assigned screen
        $screen = $staff->screens()->first();
        abort_unless($screen, 403, 'Screen not assigned');

        /* ============================
         | BASE QUERY (SCREEN-LOCKED)
         ============================ */
        $query = ScanLog::with([
            'delegate:id,firstname,lastname,phone',
            'slot:id,start_time',
            'screenSlotAssignment.movie:id,title',
        ])
        ->where('screen_id', $screen->id);

        /* ============================
         | FILTERS
         ============================ */
        if ($request->filled('day')) {
            $query->where('day', $request->day);
        }

        if ($request->filled('slot_id')) {
            $query->where('slot_id', $request->slot_id);
        }

        if ($request->filled('movie_id')) {
            $query->whereHas('screenSlotAssignment', function ($q) use ($request) {
                $q->where('movie_id', $request->movie_id);
            });
        }

        /* ============================
         | DATA
         ============================ */
        $logs = $query
            ->orderByDesc('scanned_at')
            ->limit(2000)
            ->get();

        /* ============================
         | FILTER DROPDOWNS (SCREEN-SCOPED)
         ============================ */

        // Slots used by this screen
        $slots = Slot::whereIn(
            'id',
            ScreenSlotAssignment::where('screen_id', $screen->id)
                ->distinct()
                ->pluck('slot_id')
        )
        ->orderBy('start_time')
        ->get(['id', 'start_time']);

        // Movies used by this screen
        $movies = Movie::whereIn(
            'id',
            ScreenSlotAssignment::where('screen_id', $screen->id)
                ->distinct()
                ->pluck('movie_id')
        )
        ->orderBy('title')
        ->get(['id', 'title']);

        return view('dashboard.staff.index', [
            'logs'    => $logs,
            'slots'   => $slots,
            'movies'  => $movies,
            'filters' => $request->only(['day', 'slot_id', 'movie_id']),
        ]);
    }

    /**
     * Export Excel (same filters)
     */
    public function exportExcel(Request $request)
    {
        $staff = auth()->user();
        abort_unless($staff, 401);

        $screen = $staff->screens()->first();
        abort_unless($screen, 403);

        $query = ScanLog::with([
            'delegate:id,firstname,lastname,phone',
            'slot:id,start_time',
            'screenSlotAssignment.movie:id,title',
        ])
        ->where('screen_id', $screen->id);

        if ($request->filled('day')) {
            $query->where('day', $request->day);
        }

        if ($request->filled('slot_id')) {
            $query->where('slot_id', $request->slot_id);
        }

        if ($request->filled('movie_id')) {
            $query->whereHas('screenSlotAssignment', function ($q) use ($request) {
                $q->where('movie_id', $request->movie_id);
            });
        }

        return Excel::download(
            new StaffScanLogsExport($query),
            'staff_scan_reports.xlsx'
        );
    }
}
