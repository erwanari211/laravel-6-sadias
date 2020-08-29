<?php

namespace Modules\ExampleDocuments\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Excel;
use App\Imports\ToCollectionImport;

class ImportExcelController extends Controller
{
    public function displayImportForm()
    {
        return view('exampledocuments::excel.imports.import-form');
    }

    public function import(Request $request)
    {
        request()->validate([
            'excel' => 'required|mimes:xls,xlsx'
        ]);

        $file = request('excel');
        if ($file) {
            $import = new ToCollectionImport;
            Excel::import($import, $file);

            $excelData = $import->getData();
            session()->flash('successMessage', 'Exported');
            session()->flash('importData', $excelData);
        }

        return redirect()->back();
    }
}
