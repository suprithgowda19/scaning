<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SchedulerExcelImport;
use Throwable;

class SchedulerImportController extends Controller
{
    /**
     * Show import form
     */
    public function form()
    {
        return view('admin.schedulers.import');
    }

    /**
     * Handle Excel / CSV import
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $import = new SchedulerExcelImport();

            Excel::import($import, $request->file('file'));

            // Single source of truth
            $summary = array_merge([
                'created'   => 0,
                'updated'   => 0,
                'skipped'   => 0,
                'errors'    => 0,
                'errorRows' => [],
            ], $import->getSummary());

            // ✅ ONLY flash summary
            return redirect()
                ->route('admin.schedulers.index')
                ->with('import_summary', $summary);

        } catch (ValidationException $e) {

            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();

        } catch (Throwable $e) {

            report($e);

            return redirect()
                ->back()
                ->withErrors([
                    'file' => 'Import failed due to an internal error. Please check the file format.',
                ]);
        }
    }
}
