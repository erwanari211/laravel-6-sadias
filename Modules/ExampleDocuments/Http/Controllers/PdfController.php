<?php

namespace Modules\ExampleDocuments\Http\Controllers;

use PDF;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class PdfController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $faker = Faker::create('id_ID');

        $users = [];
        $totalItems = 10;
        for($i = 1; $i <= $totalItems; $i++){
            $users[] = [
                'name' => $faker->name,
            ];
        }

        config()->set('dompdf.defines.enable_php', true); // for page number

        $view = 'exampledocuments::pdf.index';
        $pdfData = compact('users');

        // return view('exampledocuments::pdf.index', $pdfData);

        $pdf = PDF::loadView($view, $pdfData);

        $random = time().'-'.strtolower(Str::random(8));
        $filename = 'export-pdf-'.$random.'.pdf';
        return $pdf->download($filename);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('exampledocuments::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('exampledocuments::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('exampledocuments::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
