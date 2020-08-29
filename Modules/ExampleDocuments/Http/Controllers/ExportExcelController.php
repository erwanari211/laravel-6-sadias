<?php

namespace Modules\ExampleDocuments\Http\Controllers;

use Excel;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Exports\FromCollectionWithViewExport;
use App\Exports\FromCollectionWithViewMultipleSheetsExport;

class ExportExcelController extends Controller
{
    public $viewDirectory = 'exampledocuments::excel.exports.from-views';

    public function export()
    {
        $excelData = [];
        $excelData = $this->getAllSheetData();

        $date = date('Ymd');
        $randomString = Str::random(8).'-'.time();
        $filename = "export-excel-{$date}-{$randomString}.xlsx";
        $excel = new FromCollectionWithViewMultipleSheetsExport($excelData);
        return Excel::download($excel, $filename);
    }

    public function getAllSheetData()
    {
        $sheetData = [];
        $sheetData['users'] = $this->getUsersSheetData();
        $sheetData['companies'] = $this->getCompaniesSheetData();
        $sheetData['custom'] = $this->getCustomSheetData();

        return $sheetData;
    }

    public function getUsers()
    {
        $faker = Faker::create('id_ID');
        $users = [];
        $totalItems = 10;
        for($i = 1; $i <= $totalItems; $i++){
            $users[] = [
                'name' => $faker->name,
                'age' => $faker->numberBetween(25, 40),
            ];
        }

        return $users;
    }

    public function getCompanies()
    {
        $faker = Faker::create();
        $companies = [];
        $totalItems = 5;
        for($i = 1; $i <= $totalItems; $i++){
            $companies[] = [
                'company' => $faker->company,
                'catchPhrase' => $faker->catchPhrase,
            ];
        }

        return $companies;
    }

    public function getUsersSheetData()
    {
        $viewDirectory = $this->viewDirectory;

        $users = $this->getUsers();

        $sheetname = 'users';
        $view = $viewDirectory . '.users';
        $data = compact('users');
        // return view($view, $data);

        $sheetData = [
            'sheetname' => $sheetname,
            'view' => $view,
            'data' => $data,
        ];

        return $sheetData;
    }

    public function getCustomSheetData()
    {
        $viewDirectory = $this->viewDirectory;

        $users = $this->getUsers();

        $sheetname = 'custom';
        $view = $viewDirectory . '.custom';
        $data = compact('users');
        // return view($view, $data);

        $sheetData = [
            'sheetname' => $sheetname,
            'view' => $view,
            'data' => $data,
        ];

        return $sheetData;
    }

    public function getCompaniesSheetData()
    {
        $viewDirectory = $this->viewDirectory;

        $companies = $this->getCompanies();

        $sheetname = 'companies';
        $view = $viewDirectory . '.companies';
        $data = compact('companies');
        // return view($view, $data);

        $sheetData = [
            'sheetname' => $sheetname,
            'view' => $view,
            'data' => $data,
        ];

        return $sheetData;
    }


}
