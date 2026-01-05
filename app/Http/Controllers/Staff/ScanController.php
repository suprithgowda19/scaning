<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\DelegateForm;
use App\Models\ScanLog;
use App\Models\Scheduler;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Carbon;

class ScanController extends Controller
{

    public function index()
    {
        $staff = auth()->user();
        abort_unless($staff, 401);

        $screen = $staff->screens()
            ->wherePivot('active', true)
            ->firstOrFail();

        // Resolve active scheduler
        $scheduler = $this->resolveActiveScheduler($screen->id);
        abort_unless($scheduler, 403, 'No active show');

        // Category stats
        $categories = ScanLog::where('scheduler_id', $scheduler->id)
            ->selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->pluck('count', 'category')
            ->toArray();

        $entered = array_sum($categories);

        return view('staff.scan', [
            'screen'    => $screen,
            'scheduler' => $scheduler,
            'stats'     => [
                'capacity'   => $screen->capacity,
                'entered'    => $entered,
                'remaining'  => max(0, $screen->capacity - $entered),
                'categories' => $categories,
            ],
        ]);
    }
    public function scan(Request $request)
    {
        $request->validate([
            'uuid' => 'required|string',
        ]);

        $staff = auth()->user();
        abort_unless($staff, 401);

        $screen = $staff->screens()
            ->wherePivot('active', true)
            ->firstOrFail();

        $scheduler = $this->resolveActiveScheduler($screen->id);
        if (! $scheduler) {
            return response()->json([
                'status'  => 'rejected',
                'message' => 'Scanning window closed',
            ], 403);
        }

        /* ------------------------------
     | Normalize input
     ------------------------------*/
        $raw = trim($request->uuid);
        $value = preg_match('/([a-f0-9-]{36})/i', $raw, $m)
            ? strtoupper($m[1])
            : strtoupper($raw);

        /* ------------------------------
     | Delegate lookup
     ------------------------------*/
        $delegate = DelegateForm::query()
            ->whereRaw('UPPER(uuid) = ?', [$value])
            ->orWhereRaw('UPPER(form_no) = ?', [$value])
            ->first();

        if (! $delegate) {
            return response()->json([
                'status'  => 'rejected',
                'message' => 'Invalid QR / Form No',
            ]);
        }

        /* ------------------------------
     | Base query (REUSED)
     ------------------------------*/
        $scanBase = ScanLog::where('scheduler_id', $scheduler->id);

        /* ------------------------------
     | Capacity check
     ------------------------------*/
        if ($scanBase->count() >= $screen->capacity) {
            return response()->json([
                'status'  => 'rejected',
                'message' => 'Screen capacity full',
            ], 403);
        }

        /* ------------------------------
     | Insert scan
     ------------------------------*/
        try {
            ScanLog::create([
                'scheduler_id'     => $scheduler->id,
                'delegate_form_id' => $delegate->id,
                'uuid'             => $delegate->uuid,
                'form_no'          => $delegate->form_no,
                'category'         => $delegate->category,
                'screen_id'        => $screen->id,
                'scanned_by'       => $staff->id,
                'scanned_at'       => now(),
            ]);
        } catch (QueryException $e) {
            if ((int) ($e->errorInfo[1] ?? 0) === 1062) {
                return response()->json([
                    'status'  => 'duplicate',
                    'message' => 'Already scanned for this show',
                ]);
            }
            throw $e;
        }

        /* ------------------------------
     | Stats (single query)
     ------------------------------*/
        $categories = ScanLog::where('scheduler_id', $scheduler->id)
            ->selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->pluck('count', 'category')
            ->toArray();

        $entered   = array_sum($categories);
        $remaining = max(0, $screen->capacity - $entered);

        return response()->json([
            'status'   => 'valid',
            'delegate' => [
                'form_no'  => $delegate->form_no,
                'name'     => trim($delegate->firstname . ' ' . $delegate->lastname),
                'category' => $delegate->category,
            ],
            'show' => [
                'title'      => $scheduler->movie_title ?? 'Screening',
                'start_time' => Carbon::parse($scheduler->start_time)->format('h:i A'),
            ],
            'stats' => [
                'entered'    => $entered,
                'remaining'  => $remaining,
                'categories' => $categories,
            ],
        ]);
    }

    private function resolveActiveScheduler(int $screenId): ?Scheduler
    {
        $now = now();

        $schedulers = Scheduler::where('screen_id', $screenId)
            ->whereDate('show_date', $now->toDateString())
            ->orderBy('start_time')
            ->get();

        foreach ($schedulers as $i => $scheduler) {

            $start = $scheduler->start_time instanceof Carbon
                ? $scheduler->start_time
                : Carbon::parse($scheduler->start_time);

            $accessStart = $start->copy()->subMinutes(30);

            $next = $schedulers[$i + 1] ?? null;
            $accessEnd = null;

            if ($next) {
                $nextStart = $next->start_time instanceof Carbon
                    ? $next->start_time
                    : Carbon::parse($next->start_time);

                $accessEnd = $nextStart->copy()->subMinutes(30);
            }

            if (
                $now->greaterThanOrEqualTo($accessStart) &&
                ($accessEnd === null || $now->lessThan($accessEnd))
            ) {
                return $scheduler;
            }
        }

        return null;
    }
}
