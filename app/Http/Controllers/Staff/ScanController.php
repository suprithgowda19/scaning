<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\DelegateForm;
use App\Models\ScanLog;
use App\Models\Screen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScanController extends Controller
{
    /**
     * Scan dashboard page
     */
    public function index()
    {
        $staff = auth()->user();
        if (! $staff) {
            abort(401);
        }

        $screen = $staff->screens()->first();
        if (! $screen) {
            abort(403, 'Screen not assigned');
        }

        // ===== READ-ONLY STATS (for Blade cards) =====
        $entered = ScanLog::where('screen_id', $screen->id)->count();

        $categories = ScanLog::where('screen_id', $screen->id)
            ->select('category', DB::raw('COUNT(*) as count'))
            ->groupBy('category')
            ->pluck('count', 'category')   // 🔑 KEY FIX
            ->toArray();

        $stats = [
            'capacity'   => $screen->capacity,
            'entered'    => $entered,
            'remaining'  => max(0, $screen->capacity - $entered),
            'categories' => $categories,
        ];

        return view('staff.scan', compact('stats'));
    }

    /**
     * Handle QR scan
     * Accepts UUID or QR_COUNT
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

        $screen = $staff->screens()->first();
        if (! $screen) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Screen not assigned',
            ], 403);
        }

        $raw   = trim($request->uuid);
        $value = preg_replace('/^UUID:\s*/i', '', $raw);

        $delegate = DelegateForm::where('uuid', $value)
            ->orWhere('qr_count', $value)
            ->first();

        if (! $delegate) {
            return response()->json([
                'status'  => 'rejected',
                'message' => 'Invalid QR / UUID',
            ]);
        }

        if (
            ScanLog::where('delegate_form_id', $delegate->id)
                ->where('screen_id', $screen->id)
                ->exists()
        ) {
            return response()->json([
                'status'  => 'duplicate',
                'message' => 'Already scanned for this screen',
            ]);
        }

        try {
            DB::transaction(function () use ($delegate, $screen, $staff) {

                $currentCount = ScanLog::where('screen_id', $screen->id)
                    ->lockForUpdate()
                    ->count();

                if ($currentCount >= $screen->capacity) {
                    throw new \RuntimeException('Screen capacity full');
                }

                ScanLog::create([
                    'delegate_form_id' => $delegate->id,
                    'uuid'             => $delegate->uuid,
                    'screen_id'        => $screen->id,
                    'scanned_by'       => $staff->id,
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
     * Live stats (API)
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

        return response()->json([
            'total' => ScanLog::where('screen_id', $screen->id)->count(),

            'byCategory' => ScanLog::where('screen_id', $screen->id)
                ->select('category', DB::raw('COUNT(*) as count'))
                ->groupBy('category')
                ->orderBy('category')
                ->get(),

            'lastScan' => ScanLog::where('screen_id', $screen->id)
                ->latest('scanned_at')
                ->first(),
        ]);
    }
}
