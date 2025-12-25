<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\DelegateForm;
use App\Models\ScanLog;
use App\Models\ScreenSlotAssignment;
use App\Services\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ScanController extends Controller
{
    /* ============================
     | DASHBOARD
     ============================ */
    public function index()
    {
        $staff = auth()->user();
        abort_unless($staff, 401);

        $screen = $staff->screens()->first();
        abort_unless($screen, 403);

        $activeSSA = $this->resolveActiveSSA($screen->id);
        abort_unless($activeSSA, 403);

        $day = $this->resolveFestivalDay();

        $stats = ScanLog::where([
            'screen_id' => $screen->id,
            'day'       => $day,
            'slot_id'   => $activeSSA->slot_id,
        ])
            ->selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->get();

        $entered = $stats->sum('count');

        return view('staff.scan', [
            'screen'    => $screen,
            'activeSSA' => $activeSSA,
            'stats'     => [
                'capacity'   => $screen->capacity,
                'entered'    => $entered,
                'remaining'  => max(0, $screen->capacity - $entered),
                'categories' => $stats->pluck('count', 'category')->toArray(),
            ],
        ]);
    }

    /* ============================
     | SCAN API (HOT PATH)
     ============================ */
    public function scan(Request $request)
    {
        $request->validate(['uuid' => 'required|string']);

        $staff = auth()->user();
        abort_unless($staff, 401);

        $screen = $staff->screens()->first();
        abort_unless($screen, 403);

        $activeSSA = $this->resolveActiveSSA($screen->id);
        if (! $activeSSA) {
            return response()->json([
                'status'  => 'error',
                'message' => 'No active show at this time',
            ], 403);
        }

        $day   = $this->resolveFestivalDay();
        $value = trim(preg_replace('/^UUID:\s*/i', '', $request->uuid));

        /**
         * Delegate lookup (index-friendly)
         */
        $delegate =
            DelegateForm::where('uuid', $value)->first()
            ?? DelegateForm::where('qr_count', $value)->first()
            ?? DelegateForm::where('form_no', $value)->first();

        if (! $delegate) {
            return response()->json([
                'status'  => 'rejected',
                'message' => 'Invalid QR / UUID / Form No',
            ]);
        }

        /**
         * Duplicate prevention (cheap, indexed)
         */
        if (ScanLog::where([
            'uuid'      => $delegate->uuid,
            'screen_id' => $screen->id,
            'day'       => $day,
            'slot_id'   => $activeSSA->slot_id,
        ])->exists()) {
            return response()->json([
                'status'  => 'duplicate',
                'message' => 'Already scanned for this show',
            ]);
        }

        /**
         * Capacity check — early stop
         */
        $capacityReached = ScanLog::where([
            'screen_id' => $screen->id,
            'day'       => $day,
            'slot_id'   => $activeSSA->slot_id,
        ])
            ->limit($screen->capacity)
            ->count() >= $screen->capacity;

        if ($capacityReached) {
            return response()->json([
                'status'  => 'rejected',
                'message' => 'Screen capacity full',
            ], 403);
        }

        /**
         * FAST WRITE PATH (no model hydration)
         */
        DB::table('scan_logs')->insert([
            'delegate_form_id' => $delegate->id,
            'uuid'             => $delegate->uuid,
            'screen_id'        => $screen->id,
            'day'              => $day,
            'slot_id'          => $activeSSA->slot_id,
            'form_no'          => $delegate->form_no,
            'category'         => $delegate->category,
            'scanned_at'       => now(),
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        return response()->json([
            'status'   => 'valid',
            'delegate' => [
                'form_no'  => $delegate->form_no,
                'name'     => trim($delegate->firstname . ' ' . $delegate->lastname),
                'email'    => $delegate->email,
                'category' => $delegate->category,
            ],
        ]);
    }

    /* ============================
     | HELPERS (MAX OPTIMIZED)
     ============================ */

    private function resolveFestivalDay(): int
    {
        static $day = null;

        if ($day !== null) {
            return $day;
        }

        $start = DB::table('settings')
            ->where('key', 'festival_start_date')
            ->value('value');

        return $day = max(
            1,
            Carbon::parse($start)->startOfDay()->diffInDays(now()) + 1
        );
    }

    private function resolveActiveSSA(int $screenId): ?ScreenSlotAssignment
    {
        $now = time();

        $graceBefore = Settings::int('scan_grace_before', 0) * 60;
        $graceAfter  = Settings::int('scan_grace_after', 0) * 60;

        return ScreenSlotAssignment::query()
            ->select(['id', 'slot_id', 'movie_id'])
            ->with([
                'slot:id,start_time',
                'movie:id,duration',
            ])
            ->where('screen_id', $screenId)
            ->get()
            ->first(function ($ssa) use ($now, $graceBefore, $graceAfter) {

                $slotStart = strtotime($ssa->slot->start_time);
                $slotEnd   = $slotStart + (($ssa->movie->duration ?: 120) * 60);

                return $now >= ($slotStart - $graceBefore)
                    && $now <= ($slotEnd + $graceAfter);
            });
    }
}
