<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SchedulerExcelImport;

class SchedulerImportController extends Controller
{
    public function form()
    {
        return view('admin.schedulers.import');
    }

    /**
     * Handle Excel import
     */
    public function import(Request $request)
    {
        
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $import = new SchedulerExcelImport();
        Excel::import($import, $request->file('file'));

        $summary = $import->getSummary();

        return redirect()
            ->route('admin.schedulers.index')
            ->with('success', 'Scheduler import completed')
            ->with('import_summary', $summary);
    }
}
