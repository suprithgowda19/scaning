<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ScanLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\ScanLogsExport;

class StaffDashboardController extends Controller
{
    /**
     * Staff Dashboard
     * - Normal page load → Blade
     * - AJAX → DataTable JSON
     */
    public function index(Request $request)
    {
        $staff = auth()->user();
        if (! $staff) {
            abort(401);
        }

        // SECURITY: screen-scoped
        $screen = $staff->screens()->first();
        if (! $screen) {
            abort(403, 'Screen not assigned');
        }

        /* ======================
         | AJAX REQUEST (DataTable)
         ====================== */
        if ($request->ajax()) {
            return $this->datatableResponse($request, $screen->id);
        }

        /* ======================
         | DASHBOARD STATS
         ====================== */
        $entered = ScanLog::where('screen_id', $screen->id)->count();

        $categories = ScanLog::where('screen_id', $screen->id)
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

        return view('dashboard.staff.index', compact('stats'));
    }

    /**
     * DataTable JSON response
     */
    protected function datatableResponse(Request $request, int $screenId)
    {
        $query = ScanLog::with('delegate')
            ->where('screen_id', $screenId);

        // Date filter
        if ($request->filled('date')) {
            $query->whereDate('scanned_at', $request->date);
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $logs = $query
            ->orderByDesc('scanned_at')
            ->get();

        $data = [];
        foreach ($logs as $index => $log) {
            $data[] = [
                'index'      => $index + 1,
                'form_no'    => $log->form_no,
                'name'       => optional($log->delegate)
                                    ? trim($log->delegate->firstname . ' ' . $log->delegate->lastname)
                                    : '-',
                'phone'      => $log->delegate->phone ?? '-',
                'category'   => $log->category,
                'status'     => ucfirst($log->status),
                'scanned_at' => optional($log->scanned_at)->format('Y-m-d H:i:s'),
            ];
        }

        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * Export Excel (same filters)
     */
    public function exportExcel(Request $request)
    {
        return Excel::download(
            new ScanLogsExport($this->exportQuery($request)),
            'scan_logs.xlsx'
        );
    }

    /**
     * Export PDF (same filters)
     */
    public function exportPdf(Request $request)
    {
        $logs = $this->exportQuery($request)->get();

        $pdf = Pdf::loadView(
            'dashboard.staff.exports.scans_pdf',
            compact('logs')
        )->setPaper('a4', 'landscape');

        return $pdf->download('scan_logs.pdf');
    }

    /**
     * Shared query for exports
     */
    protected function exportQuery(Request $request)
    {
        $staff = auth()->user();
        if (! $staff) {
            abort(401);
        }

        $screen = $staff->screens()->first();
        if (! $screen) {
            abort(403);
        }

        $query = ScanLog::with('delegate')
            ->where('screen_id', $screen->id);

        if ($request->filled('date')) {
            $query->whereDate('scanned_at', $request->date);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        return $query->orderByDesc('scanned_at');
    }
}
