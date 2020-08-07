<?php

namespace Modules\ExampleBlog\Services;

use Illuminate\Support\Str;
use Modules\ExampleBlog\Models\Post;
use Modules\ExampleBlog\Http\Resources\PostResource;

class PostService
{
    public $model;
    public $perPage = 10;
    public $data;

    public function __construct()
    {
        $this->model = new Post;
    }

    public function getData()
    {
        $user = auth()->user();
        $data = $this->model->where('author_id', $user->id)
            ->latest()
            ->paginate($this->perPage);
        return PostResource::collection($data);
    }

    public function getItem($id)
    {
        if(is_numeric($id)){
            $item = $this->model->findOrFail($id);
        }
        if($id instanceof Post){
            $item = $id;
        }
        return new PostResource($item);
    }

    public function create($data)
    {
        $this->data = $data;
        $this->beforeCreate();
        $item = $this->model;
        $item = $item->create($this->data);
        return $item;
    }

    public function update($item, $data)
    {
        $this->data = $data;
        $this->beforeUpdate();
        $item->update($this->data);
        return $item;
    }

    public function delete($item)
    {
        return $item->delete();
    }

    public function beforeCreate()
    {
        $user = auth()->user();
        $this->data['postable_id'] = $user->id;
        $this->data['postable_type'] = get_class($user);
        $this->data['unique_code'] = Str::random();
    }

    public function beforeUpdate()
    {
        //
    }
}
