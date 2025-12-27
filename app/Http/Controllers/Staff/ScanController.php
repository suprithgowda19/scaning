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
    /* ==========================================================
     | DASHBOARD
     ========================================================== */
    public function index()
    {
        $staff = auth()->user();
        abort_unless($staff, 401);

        $screen = $staff->screens()->first();
        abort_unless($screen, 403, 'Screen not assigned');

        $activeSSA = $this->resolveActiveSSA($screen->id);
        abort_unless($activeSSA, 403, 'No active show');

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

    /* ==========================================================
     | HOT PATH — SCAN
     ========================================================== */
    public function scan(Request $request)
    {
        $request->validate(['uuid' => 'required|string']);

        $staff = auth()->user();
        abort_unless($staff, 401);

        $screen = $staff->screens()->first();
        abort_unless($screen, 403, 'Screen not assigned');

        $activeSSA = $this->resolveActiveSSA($screen->id);
        if (! $activeSSA) {
            return response()->json([
                'status'  => 'error',
                'message' => 'No active show at this time',
            ], 403);
        }

        $day   = $this->resolveFestivalDay();
        $value = trim(preg_replace('/^UUID:\s*/i', '', $request->uuid));

        // Index-only lookups
        $delegate =
            DelegateForm::where('uuid', $value)->first()
            ?? DelegateForm::where('form_no', $value)->first();

        if (! $delegate) {
            return response()->json([
                'status'  => 'rejected',
                'message' => 'Invalid QR / UUID / Form No',
            ]);
        }

        try {
            DB::transaction(function () use ($delegate, $screen, $activeSSA, $day) {

                $currentCount = ScanLog::where([
                        'screen_id' => $screen->id,
                        'day'       => $day,
                        'slot_id'   => $activeSSA->slot_id,
                    ])
                    ->lockForUpdate()
                    ->count();

                if ($currentCount >= $screen->capacity) {
                    throw new \RuntimeException('Screen capacity full');
                }

                ScanLog::create([
                    'delegate_form_id' => $delegate->id,
                    'uuid'             => $delegate->uuid,
                    'screen_id'        => $screen->id,
                    'day'              => $day,
                    'slot_id'          => $activeSSA->slot_id,
                    'form_no'          => $delegate->form_no,
                    'category'         => $delegate->category,
                    'scanned_at'       => now(),
                ]);
            });
        } catch (\RuntimeException $e) {
            return response()->json([
                'status'  => 'rejected',
                'message' => $e->getMessage(),
            ], 403);
        }

        return response()->json([
            'status'   => 'valid',
            'delegate' => [
                'form_no'  => $delegate->form_no,
                'name'     => trim($delegate->firstname . ' ' . $delegate->lastname),
                'email'    => $delegate->email,
                'category' => $delegate->category,
            ],
            'movie' => [
                'title'    => $activeSSA->movie->title,
                'language' => $activeSSA->movie->language,
                'duration' => $activeSSA->movie->duration,
            ],
            'slot' => [
                'start_time' => $activeSSA->slot->start_time,
            ],
        ]);
    }

    /* ==========================================================
     | HELPERS
     ========================================================== */

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
                'movie:id,title,language,duration',
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
