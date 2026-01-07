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
        $screens = Screen::orderBy('name')->get(['id', 'name', 'capacity']);

        /*
        |--------------------------------------------------------------------------
        | 1. Resolve LATEST scheduler per screen (today)
        |--------------------------------------------------------------------------
        | This avoids "first show of the day" bug
        */
        $latestSchedulers = Scheduler::whereDate('show_date', $today)
            ->select('id', 'screen_id', 'movie_title')
            ->orderBy('start_time', 'desc')
            ->get()
            ->groupBy('screen_id')
            ->map(fn ($group) => $group->first());

        /*
        |--------------------------------------------------------------------------
        | 2. Aggregate scan counts in ONE query
        |--------------------------------------------------------------------------
        */
        $scanCounts = ScanLog::query()
            ->join('schedulers', 'scan_logs.scheduler_id', '=', 'schedulers.id')
            ->whereDate('schedulers.show_date', $today)
            ->select('schedulers.screen_id', DB::raw('COUNT(*) as total'))
            ->groupBy('schedulers.screen_id')
            ->pluck('total', 'schedulers.screen_id');

        /*
        |--------------------------------------------------------------------------
        | 3. Build live cards (NO queries inside loop)
        |--------------------------------------------------------------------------
        */
        $liveCards = $screens->map(function ($screen) use ($latestSchedulers, $scanCounts) {
            $scheduler = $latestSchedulers[$screen->id] ?? null;

            return [
                'screen_name'   => $screen->name,
                'movie_title'   => $scheduler?->movie_title ?? 'N/A',
                'capacity'      => (int) ($screen->capacity ?? 0),
                'scanned_count' => (int) ($scanCounts[$screen->id] ?? 0),
            ];
        });

        /*
        |--------------------------------------------------------------------------
        | 4. Analytics (filters)
        |--------------------------------------------------------------------------
        */
        $filterDate   = $request->input('filter_date', $today->toDateString());
        $filterScreen = $request->input('filter_screen_id');

        $analyticsQuery = ScanLog::query()
            ->join('schedulers', 'scan_logs.scheduler_id', '=', 'schedulers.id')
            ->whereDate('schedulers.show_date', $filterDate);

        if ($filterScreen) {
            $analyticsQuery->where('schedulers.screen_id', $filterScreen);
        }

        $categoryStats = (clone $analyticsQuery)
            ->select('scan_logs.category', DB::raw('COUNT(*) as total'))
            ->groupBy('scan_logs.category')
            ->pluck('total', 'scan_logs.category');

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

        /*
        |--------------------------------------------------------------------------
        | 5. AJAX poll response
        |--------------------------------------------------------------------------
        */
        if ($request->ajax()) {
            return response()->json([
                'liveCards'     => $liveCards,
                'categoryStats' => $categoryStats,
                'screenStats'   => $screenStats,
            ]);
        }

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
