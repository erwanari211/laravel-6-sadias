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
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            $tags = $this->user->tags->pluck('name', 'id');

            $this->service = new PostService;
            $this->data = [
                'dropdown' => [
                    'statuses' => [
                        'draft' =>  __('exampleblog::post.form.dropdown.statuses.draft'),
                        'published' =>  __('exampleblog::post.form.dropdown.statuses.published'),
                        'archived' =>  __('exampleblog::post.form.dropdown.statuses.archived'),
                    ]
                ],
                'tags' => $tags,
            ];

            return $next($request);
        });
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
        $dropdown = $this->data['dropdown'];
        $tags = $this->data['tags'];

        return view('exampleblog::posts.create', compact(
            'post',
            'dropdown', 'tags'
        ));
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
        $dropdown = $this->data['dropdown'];
        $tags = $this->data['tags'];
        return view('exampleblog::posts.show', compact(
            'post',
            'dropdown', 'tags'
        ));
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        $dropdown = $this->data['dropdown'];
        $tags = $this->data['tags'];
        return view('exampleblog::posts.edit', compact(
            'post',
            'dropdown', 'tags'
        ));
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
