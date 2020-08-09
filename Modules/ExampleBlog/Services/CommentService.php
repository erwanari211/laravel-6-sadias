<?php

namespace Modules\ExampleBlog\Services;

use Modules\ExampleBlog\Models\Comment;
use Modules\ExampleBlog\Http\Resources\CommentResource;

class CommentService
{
    public $model;
    public $perPage = 10;
    public $data;

    public function __construct()
    {
        $this->model = new Comment;
    }

    public function getData()
    {
        $user = auth()->user();
        $data = $this->model->where('author_id', $user->id)
            ->latest()
            ->paginate($this->perPage);
        return CommentResource::collection($data);
    }

    public function getItem($id)
    {
        if(is_numeric($id)){
            $item = $this->model->findOrFail($id);
        }
        if($id instanceof Comment){
            $item = $id;
        }
        return new CommentResource($item);
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
        //
    }

    public function beforeUpdate()
    {
        //
    }
}
