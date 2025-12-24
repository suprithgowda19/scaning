<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\DelegateForm;
use App\Models\ScanLog;
use App\Models\ScreenSlotAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScanController extends Controller
{
    /**
     * Scan dashboard page
     * Shows:
     * - Screen name
     * - Active Day & Slot (from SSA)
     * - Live stats (current show only)
     */
    public function index()
    {
        $staff = auth()->user();
        if (! $staff) {
            abort(401);
        }

        // Resolve staff → screen
        $screen = $staff->screens()->first();
        if (! $screen) {
            abort(403, 'Screen not assigned');
        }

        // Resolve ACTIVE SSA (MANDATORY)
        $activeSSA = ScreenSlotAssignment::with(['slot', 'movie'])
            ->where('screen_id', $screen->id)
            ->where('status', 'active')
            ->first();

        if (! $activeSSA) {
            abort(403, 'No active show for this screen. Scanning disabled.');
        }

        // ===== STATS (CURRENT SHOW ONLY) =====
        $entered = ScanLog::where('screen_id', $screen->id)
            ->where('day', $activeSSA->day)
            ->where('slot_id', $activeSSA->slot_id)
            ->count();

        $categories = ScanLog::where('screen_id', $screen->id)
            ->where('day', $activeSSA->day)
            ->where('slot_id', $activeSSA->slot_id)
            ->select('category', DB::raw('COUNT(*) as count'))
            ->groupBy('category')
            ->pluck('count', 'category')
            ->toArray();

        $stats = [
            'capacity'   => $screen->capacity,
            'entered'    => $entered,
            'remaining'  => max(0, $screen->capacity - $entered),
            'categories' => $categories,
        ];

        return view('staff.scan', compact(
            'screen',
            'activeSSA',
            'stats'
        ));
    }

    /**
     * Handle QR / UUID scan
     */
    public function scan(Request $request)
    {
        $request->validate([
            'uuid' => 'required|string',
        ]);

        $staff = auth()->user();
        if (! $staff) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Unauthenticated',
            ], 401);
        }

        // Resolve staff → screen
        $screen = $staff->screens()->first();
        if (! $screen) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Screen not assigned',
            ], 403);
        }

        // Resolve ACTIVE SSA (GATE)
        $activeSSA = ScreenSlotAssignment::where('screen_id', $screen->id)
            ->where('status', 'active')
            ->first();

        if (! $activeSSA) {
            return response()->json([
                'status'  => 'error',
                'message' => 'No active show for this screen. Scanning disabled.',
            ], 403);
        }

        // Normalize scanner input
        $raw   = trim($request->uuid);
        $value = preg_replace('/^UUID:\s*/i', '', $raw);

        // Resolve delegate
        $delegate = DelegateForm::where('uuid', $value)
            ->orWhere('qr_count', $value)
            ->first();

        if (! $delegate) {
            return response()->json([
                'status'  => 'rejected',
                'message' => 'Invalid QR / UUID',
            ]);
        }

        // SSA-SCOPED duplicate check
        if (
            ScanLog::where('uuid', $delegate->uuid)
                ->where('screen_id', $screen->id)
                ->where('day', $activeSSA->day)
                ->where('slot_id', $activeSSA->slot_id)
                ->exists()
        ) {
            return response()->json([
                'status'  => 'duplicate',
                'message' => 'Already scanned for this show',
            ]);
        }

        try {
            DB::transaction(function () use ($delegate, $screen, $activeSSA) {

                // SSA-SCOPED capacity check
                $currentCount = ScanLog::where('screen_id', $screen->id)
                    ->where('day', $activeSSA->day)
                    ->where('slot_id', $activeSSA->slot_id)
                    ->lockForUpdate()
                    ->count();

                if ($currentCount >= $screen->capacity) {
                    throw new \RuntimeException('Screen capacity full');
                }

                // Persist scan (IMMUTABLE FACT)
                ScanLog::create([
                    'delegate_form_id' => $delegate->id,
                    'uuid'             => $delegate->uuid,
                    'screen_id'        => $screen->id,

                    // 🔒 SSA SNAPSHOT
                    'day'              => $activeSSA->day,
                    'slot_id'          => $activeSSA->slot_id,

                    // Snapshot fields
                    'form_no'          => $delegate->form_no,
                    'category'         => $delegate->category,

                    'status'           => 'valid',
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
                'category' => $delegate->category,
            ],
        ]);
    }

    /**
     * Live stats API (current show only)
     */
    public function stats()
    {
        $staff = auth()->user();
        if (! $staff) {
            return response()->json([], 401);
        }

        $screen = $staff->screens()->first();
        if (! $screen) {
            return response()->json([], 403);
        }

        $activeSSA = ScreenSlotAssignment::where('screen_id', $screen->id)
            ->where('status', 'active')
            ->first();

        if (! $activeSSA) {
            return response()->json([], 403);
        }

        return response()->json([
            'total' => ScanLog::where('screen_id', $screen->id)
                ->where('day', $activeSSA->day)
                ->where('slot_id', $activeSSA->slot_id)
                ->count(),

            'byCategory' => ScanLog::where('screen_id', $screen->id)
                ->where('day', $activeSSA->day)
                ->where('slot_id', $activeSSA->slot_id)
                ->select('category', DB::raw('COUNT(*) as count'))
                ->groupBy('category')
                ->orderBy('category')
                ->get(),

            'lastScan' => ScanLog::where('screen_id', $screen->id)
                ->where('day', $activeSSA->day)
                ->where('slot_id', $activeSSA->slot_id)
                ->latest('scanned_at')
                ->first(),
        ]);
    }
}
