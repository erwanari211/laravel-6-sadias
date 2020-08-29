<?php

namespace Modules\ExampleDocuments\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use File;
use Image;
use Carbon\Carbon;
use Str;

class ImageResizeController extends Controller
{
    public $directory = 'uploads/examples/documents/image-resize';
    public $dimensions = ['200', '300', '500'];

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $directory = $this->directory;
        $path = public_path($directory);
        $files = File::files($path);

        $dimensions = $this->dimensions;
        return view('exampledocuments::image-resize.index')->with([
            'files' => $files,
            'dimensions' => $dimensions,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('exampledocuments::image-resize.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'file' => 'required|file|image',
        ]);

        $file = request('file');
        $directory = $this->directory;

        $fileName = 'example_doc_image_resize';
        $ext = $file->getClientOriginalExtension();
        $newFileName = Str::slug($fileName).'-'.Str::random(8).'-'.time().'.'.$ext;
        Image::make($file)->save($directory . '/' . $newFileName);

        $dimensions = $this->dimensions;
        foreach ($dimensions as $dimension) {
            if (!File::isDirectory($directory . '/' . $dimension)) {
                File::makeDirectory($directory . '/' . $dimension);
            }

            $canvas = Image::canvas($dimension, $dimension);

            // $resizeImage  = Image::make($file)->fit($dimension, $dimension);
            // $resizeImage  = Image::make($file)->resize($dimension, $dimension);

            $resizeImage  = Image::make($file)->resize($dimension, $dimension, function($constraint) {
                $constraint->aspectRatio();
            });

            $canvas->insert($resizeImage, 'center');
            $canvas->save($directory . '/' . $dimension . '/' . $newFileName);
        }

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

        $dimensions = $this->dimensions;
        foreach ($dimensions as $dimension) {
            $thumbUrl = str_replace('documents/image-resize', 'documents/image-resize/'.$dimension, $url);
            delete_file($thumbUrl);
        }
        delete_file($url);

        session()->flash('successMessage', __('my_app.messages.data_deleted'));
        return redirect()->back();
    }
}
