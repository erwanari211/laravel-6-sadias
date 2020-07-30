<?php

namespace Modules\ExampleBlog\Http\Controllers\Backend;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\ExampleBlog\Http\Requests\ExampleBlogChannelRequest as ChannelRequest;
use Modules\ExampleBlog\Services\ExampleBlogChannelService as ChannelService;
use Modules\ExampleBlog\Entities\ExampleBlogChannel as Channel;

class ChannelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->service = new ChannelService;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $this->authorize('viewAny', Channel::class);

        $channels = $this->service->getData();

        return view('exampleblog::channels.index', compact('channels'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $this->authorize('create', Channel::class);

        $channel = new Channel;

        return view('exampleblog::channels.create', compact('channel'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(ChannelRequest $request)
    {
        $this->authorize('create', Channel::class);

        $data = $request->validated();
        $this->service->create($data);

        session()->flash('successMessage', __('exampleblog::channel.messages.data_created'));
        return redirect()->back();
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show(Channel $channel)
    {
        $this->authorize('view', $channel);

        return view('exampleblog::channels.show', compact('channel'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit(Channel $channel)
    {
        $this->authorize('update', $channel);

        return view('exampleblog::channels.edit', compact('channel'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(ChannelRequest $request, Channel $channel)
    {
        $this->authorize('update', $channel);

        $data = $request->validated();
        $this->service->update($channel, $data);

        session()->flash('successMessage', __('exampleblog::channel.messages.data_updated'));
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy(Channel $channel)
    {
        $this->authorize('delete', $channel);

        $this->service->delete($channel);

        session()->flash('successMessage', __('exampleblog::channel.messages.data_deleted'));
        return redirect()->back();
    }
}
