<?php

namespace Modules\ExampleDocuments\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
Use Alert;

class SweetAlertController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        // Alert::alert('Title', 'Message', 'success');

        // Alert::success('Success Title', 'Success Message');
        // Alert::info('Info Title', 'Info Message');
        // Alert::warning('Warning Title', 'Warning Message');
        // Alert::error('Error Title', 'Error Message');

        // Alert::question('Question Title', 'Question Message');
        // Alert::image('Image Title!','Image Description','https://laravel.com/img/logomark.min.svg','200','200');
        // Alert::html('Html Title', 'Html Code', 'success');

        Alert::toast('Toast Message', 'success');

        return view('exampledocuments::sweet-alert.index');
    }
}
