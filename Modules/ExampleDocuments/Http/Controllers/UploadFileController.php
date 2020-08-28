<?php

namespace Modules\ExampleDocuments\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use File;

class UploadFileController extends Controller
{
    public $directory = 'uploads/examples/documents/upload-files';

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $directory = $this->directory;
        $path = public_path($directory);
        $files = File::files($path);

        return view('exampledocuments::upload-file.index')->with([
            'files' => $files,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('exampledocuments::upload-file.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'file' => 'required|file',
        ]);

        $file = request('file');
        $directory = $this->directory;
        $filepath = upload_file($file, $directory, 'example_doc_upload_file');

        session()->flash('successMessage', __('my_app.messages.data_created'));
        return redirect()->back();
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //
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
        $url = request('url');
        delete_file($url);

        session()->flash('successMessage', __('my_app.messages.data_deleted'));
        return redirect()->back();
    }
}
