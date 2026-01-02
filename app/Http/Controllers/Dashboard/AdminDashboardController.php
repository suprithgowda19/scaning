<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ScanLog;
use App\Models\Screen;
use App\Models\Scheduler;
use App\Services\SlotResolver;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AdminScanLogsExport;

class AdminDashboardController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()?->hasRole('admin'), 403);

        return view('dashboard.admin.index', [
            'screens' => Screen::orderBy('name')->get(['id', 'name']),
            'movies'  => Scheduler::distinct()->orderBy('movie_title')->pluck('movie_title'),
            'dates'   => Scheduler::distinct()->orderBy('show_date')->pluck('show_date'),
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

        if ($request->filled('show_date')) {
            $query->whereHas('scheduler', fn ($q) =>
                $q->whereDate('show_date', $request->show_date)
            );
        }

        if ($request->filled('screen_id')) {
            $query->whereHas('scheduler', fn ($q) =>
                $q->where('screen_id', $request->screen_id)
            );
        }

        if ($request->filled('movie_title')) {
            $query->whereHas('scheduler', fn ($q) =>
                $q->where('movie_title', $request->movie_title)
            );
        }

        if ($request->filled('slot_no')) {
            $schedulerIds = SlotResolver::schedulerIdsForSlot(
                $request->show_date,
                (int) $request->slot_no,
                $request->screen_id
            );

            $schedulerIds
                ? $query->whereIn('scheduler_id', $schedulerIds)
                : $query->whereRaw('1=0');
        }

        $logs = $query
            ->orderByDesc('scanned_at')
            ->limit(3000)
            ->get()
            ->map(function ($log) {
                // 🔥 ATTACH SLOT NUMBER PER ROW
                $log->slot_no = SlotResolver::slotNoForScheduler($log->scheduler);
                return $log;
            });

        return response()->json([
            'logs'  => $logs,
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
            new AdminScanLogsExport($request->all()),
            'admin_scan_reports.xlsx'
        );
    }
}
