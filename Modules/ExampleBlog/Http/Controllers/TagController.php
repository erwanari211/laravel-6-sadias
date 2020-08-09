<?php

namespace Modules\ExampleBlog\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\ExampleBlog\Models\Tag;
use Modules\ExampleBlog\Services\TagService;
use Modules\ExampleBlog\Http\Requests\TagRequest;

class TagController extends Controller
{
    public $service;
    public $data;

    public function __construct()
    {
        $this->service = new TagService;
        $this->data = [];
    }

    public function index()
    {
        $this->authorize('viewAny', Tag::class);
        $tags = $this->service->getData();
        return view('exampleblog::tags.index', compact('tags'));
    }

    public function create()
    {
        $this->authorize('create', Tag::class);
        $tag = new Tag;
        return view('exampleblog::tags.create', compact('tag'));
    }

    public function store(TagRequest $request)
    {
        $this->authorize('create', Tag::class);
        $data = $request->validated();
        $this->service->create($data);

        $message = __('my_app.messages.data_created');
        flash($message)->success();
        return redirect()->route('example-blog.tags.index');
    }

    public function show(Tag $tag)
    {
        $this->authorize('view', $tag);
        return view('exampleblog::tags.show', compact('tag'));
    }

    public function edit(Tag $tag)
    {
        $this->authorize('update', $tag);
        return view('exampleblog::tags.edit', compact('tag'));
    }

    public function update(TagRequest $request, Tag $tag)
    {
        $this->authorize('update', $tag);
        $data = $request->validated();
        $this->service->update($tag, $data);

        $message = __('my_app.messages.data_updated');
        flash($message)->success();
        return redirect()->route('example-blog.tags.index');
    }

    public function destroy(Tag $tag)
    {
        $this->authorize('delete', $tag);
        $this->service->delete($tag);

        $message = __('my_app.messages.data_deleted');
        flash($message)->success();
        return redirect()->route('example-blog.tags.index');
    }
}
