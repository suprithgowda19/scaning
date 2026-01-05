<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Screen;
use App\Models\Scheduler;
use App\Models\ScanLog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(auth()->user()?->hasRole('admin'), 403);

        $today   = Carbon::today();
        $screens = Screen::orderBy('name')->get();

        /* ===============================
         | LIVE CARDS (TODAY)
         =============================== */
        $todaySchedulers = Scheduler::whereDate('show_date', $today)
            ->orderBy('start_time')
            ->get()
            ->groupBy('screen_id');

        $liveCards = $screens->map(function ($screen) use ($todaySchedulers) {
            $scheduler = $todaySchedulers[$screen->id][0] ?? null;

            return [
                'screen_name'   => $screen->name,
                'movie_title'   => $scheduler?->movie_title ?? 'N/A',
                'capacity'      => (int) ($screen->capacity ?? 0),
                'scanned_count' => $scheduler
                    ? ScanLog::where('scheduler_id', $scheduler->id)->count()
                    : 0,
            ];
        });

        /* ===============================
         | FILTERS
         =============================== */
        $filterDate   = $request->input('filter_date', $today->toDateString());
        $filterScreen = $request->input('filter_screen_id');

        $analyticsQuery = ScanLog::query()
            ->join('schedulers', 'scan_logs.scheduler_id', '=', 'schedulers.id')
            ->whereDate('schedulers.show_date', $filterDate);

        if ($filterScreen) {
            $analyticsQuery->where('schedulers.screen_id', $filterScreen);
        }

        /* ===============================
         | CATEGORY STATS (DB-DRIVEN)
         =============================== */
        $categoryStats = (clone $analyticsQuery)
            ->select('scan_logs.category', DB::raw('COUNT(*) as total'))
            ->groupBy('scan_logs.category')
            ->pluck('total', 'scan_logs.category');

        /* ===============================
         | BAR CHART — ALL SCREENS
         =============================== */
        $screenTotals = (clone $analyticsQuery)
            ->select('schedulers.screen_id', DB::raw('COUNT(*) as total'))
            ->groupBy('schedulers.screen_id')
            ->pluck('total', 'schedulers.screen_id');

        $screenStats = $screens->map(function ($screen) use ($screenTotals) {
            return [
                'name'  => $screen->name,
                'total' => (int) ($screenTotals[$screen->id] ?? 0),
            ];
        });

        /* ===============================
         | AJAX POLLING RESPONSE
         =============================== */
        if ($request->ajax()) {
            return response()->json([
                'liveCards'     => $liveCards,
                'categoryStats' => $categoryStats,
                'screenStats'   => $screenStats,
            ]);
        }

        /* ===============================
         | INITIAL VIEW
         =============================== */
        return view('dashboard.admin.index', compact(
            'liveCards',
            'screens',
            'filterDate',
            'filterScreen',
            'categoryStats',
            'screenStats'
        ));
    }
}
