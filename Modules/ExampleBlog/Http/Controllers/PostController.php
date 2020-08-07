<?php

namespace Modules\ExampleBlog\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\ExampleBlog\Models\Post;
use Modules\ExampleBlog\Services\PostService;
use Modules\ExampleBlog\Http\Requests\PostRequest;

class PostController extends Controller
{
    public $service;
    public $data;

    public function __construct()
    {
        $this->service = new PostService;
        $this->data = [];
    }

    public function index()
    {
        $this->authorize('viewAny', Post::class);
        $posts = $this->service->getData();
        return view('exampleblog::posts.index', compact('posts'));
    }

    public function create()
    {
        $this->authorize('create', Post::class);
        $post = new Post;
        return view('exampleblog::posts.create', compact('post'));
    }

    public function store(PostRequest $request)
    {
        $this->authorize('create', Post::class);
        $data = $request->validated();
        $this->service->create($data);

        $message = __('my_app.messages.data_created');
        flash($message)->success();
        return redirect()->route('example-blog.posts.index');
    }

    public function show(Post $post)
    {
        $this->authorize('view', $post);
        return view('exampleblog::posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        return view('exampleblog::posts.edit', compact('post'));
    }

    public function update(PostRequest $request, Post $post)
    {
        $this->authorize('update', $post);
        $data = $request->validated();
        $this->service->update($post, $data);

        $message = __('my_app.messages.data_updated');
        flash($message)->success();
        return redirect()->route('example-blog.posts.index');
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        $this->service->delete($post);

        $message = __('my_app.messages.data_deleted');
        flash($message)->success();
        return redirect()->route('example-blog.posts.index');
    }
}
