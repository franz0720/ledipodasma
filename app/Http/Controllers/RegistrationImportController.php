<?php

namespace App\Http\Controllers;

use App\Imports\RegistrationsImport;
use App\Exports\RegistrationsImportTemplateExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class RegistrationImportController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls,csv',
                'max:10240',
            ],
        ], [
            'file.required' => 'Choose an Excel or CSV file first.',
            'file.mimes' => 'Upload an .xlsx, .xls, or .csv file.',
            'file.max' => 'The file must be 10 MB or smaller.',
        ]);

        $import = new RegistrationsImport();

        try {
            Excel::import($import, $request->file('file'));
        } catch (Throwable $e) {
            report($e);

            return redirect()
                ->route('admin1', ['view' => 'registrations'])
                ->withErrors([
                    'file' => 'Import failed. Check the spreadsheet format and required columns, then try again.',
                ]);
        }

        return redirect()
            ->route('admin1', ['view' => 'registrations'])
            ->with('registration_import_summary', [
                'imported' => $import->imported,
                'duplicates' => $import->duplicates,
                'errors' => array_slice($import->errors, 0, 100),
                'error_count' => count($import->errors),
            ]);
    }

    public function template()
    {
        return Excel::download(
            new RegistrationsImportTemplateExport(),
            'registrations_import_template.xlsx'
        );
    }
}
