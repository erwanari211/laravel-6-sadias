<?php

namespace Modules\ExampleBlog\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\ExampleBlog\Models\Comment;
use Modules\ExampleBlog\Services\CommentService;
use Modules\ExampleBlog\Http\Requests\CommentRequest;

class CommentController extends Controller
{
    public $service;
    public $data;

    public function __construct()
    {
        $this->service = new CommentService;
        $this->data = [];
    }

    public function index()
    {
        $this->authorize('viewAny', Comment::class);
        $comments = $this->service->getData();
        return view('exampleblog::comments.index', compact('comments'));
    }

    public function create()
    {
        $this->authorize('create', Comment::class);
        $comment = new Comment;
        return view('exampleblog::comments.create', compact('comment'));
    }

    public function store(CommentRequest $request)
    {
        $this->authorize('create', Comment::class);
        $data = $request->validated();
        $this->service->create($data);

        $message = __('my_app.messages.data_created');
        flash($message)->success();
        return redirect()->route('example-blog.comments.index');
    }

    public function show(Comment $comment)
    {
        $this->authorize('view', $comment);
        return view('exampleblog::comments.show', compact('comment'));
    }

    public function edit(Comment $comment)
    {
        $this->authorize('update', $comment);
        return view('exampleblog::comments.edit', compact('comment'));
    }

    public function update(CommentRequest $request, Comment $comment)
    {
        $this->authorize('update', $comment);
        $data = $request->validated();
        $this->service->update($comment, $data);

        $message = __('my_app.messages.data_updated');
        flash($message)->success();
        return redirect()->route('example-blog.comments.index');
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);
        $this->service->delete($comment);

        $message = __('my_app.messages.data_deleted');
        flash($message)->success();
        return redirect()->route('example-blog.comments.index');
    }
}
