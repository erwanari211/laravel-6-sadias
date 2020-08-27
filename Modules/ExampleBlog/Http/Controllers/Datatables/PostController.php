<?php

namespace Modules\ExampleBlog\Http\Controllers\Datatables;

use App\Http\Controllers\Controller;
use Modules\ExampleBlog\Models\Post;
use DataTables;
use Modules\ExampleBlog\Services\Datatables\PostService;

class PostController extends Controller
{
    public $service;
    public $data;

    public function __construct()
    {
        $this->service = new PostService;
    }

    public function index()
    {
        $this->authorize('viewAny', Post::class);
        return view('exampleblog::posts.index-dt');
    }

    public function data()
    {
        return $this->service->getUserPosts();
    }


}
