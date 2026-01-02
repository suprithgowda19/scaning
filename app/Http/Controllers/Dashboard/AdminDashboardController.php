<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ScanLog;
use App\Models\Screen;
use App\Models\Slot;
use App\Models\Movie;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AdminScanLogsExport;

class AdminDashboardController extends Controller
{
    /**
     * Admin Scan Reports (Table + Filters + Export)
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        abort_unless($user && $user->hasRole('admin'), 403);

        // -------------------------
        // BASE QUERY (ALL DATA)
        // -------------------------
        $query = ScanLog::with([
            'delegate:id,firstname,lastname,phone',
            'screen:id,name',
            'slot:id,start_time',
            'screenSlotAssignment.movie:id,title',
        ]);

        // -------------------------
        // FILTERS (OPTIONAL)
        // -------------------------
        if ($request->filled('day')) {
            $query->where('day', $request->day);
        }

        if ($request->filled('screen_id')) {
            $query->where('screen_id', $request->screen_id);
        }

        if ($request->filled('slot_id')) {
            $query->where('slot_id', $request->slot_id);
        }

        if ($request->filled('movie_id')) {
            $query->whereHas('screenSlotAssignment', function ($q) use ($request) {
                $q->where('movie_id', $request->movie_id);
            });
        }

        // -------------------------
        // DATA (SAFE LIMIT)
        // -------------------------
        $logs = $query
            ->orderByDesc('scanned_at')
            ->limit(3000) // safety guard
            ->get();

        return view('dashboard.admin.index', [
            'logs'    => $logs,
            'screens' => Screen::orderBy('name')->get(['id', 'name']),
            'slots'   => Slot::orderBy('start_time')->get(['id', 'start_time']),
            'movies'  => Movie::orderBy('title')->get(['id', 'title']),
            'filters' => $request->only(['day', 'screen_id', 'slot_id', 'movie_id']),
        ]);
    }

    /**
     * Export Excel (same filters)
     */
    public function exportExcel(Request $request)
    {
        $user = auth()->user();
        abort_unless($user && $user->hasRole('admin'), 403);

        return Excel::download(
            new AdminScanLogsExport($request),
            'admin_scan_reports.xlsx'
        );
    }
}
