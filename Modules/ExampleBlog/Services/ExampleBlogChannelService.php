<?php

namespace Modules\ExampleBlog\Services;

use Illuminate\Support\Str;
use Modules\ExampleBlog\Entities\ExampleBlogChannel as Channel;
use Modules\ExampleBlog\Transformers\ExampleBlogChannelResource as ChannelResource;

class ExampleBlogChannelService
{
    public $model;
    public $perPage = 10;

    public function __construct()
    {
        $this->model = new Channel;
    }

    public function getData($user = null)
    {
        $user = $user ?? auth()->user();
        $data = $this->model->with('owner')->where('owner_id', $user->id)->latest()->paginate($this->perPage);
        return ChannelResource::collection($data);
    }

    public function getItem($id)
    {
        $item = $this->model->findOrFail($id)->load('owner');
        return new ChannelResource($item);
    }

    public function create($data)
    {
        $data['owner_id'] = auth()->user()->id;
        $data['slug'] = Str::slug($data['slug']);
        $item = $this->model;
        $item = $item->create($data);
        return $item;
    }

    public function update($item, $data)
    {
        unset($data['owner_id']);
        $data['slug'] = Str::slug($data['slug']);
        $item->update($data);
        return $item;
    }

    public function delete($item)
    {
        return $item->delete();
    }
}
