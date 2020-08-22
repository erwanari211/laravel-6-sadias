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
    public $publishForTeam = false;
    public $team;

    public function __construct()
    {
        $this->model = new Post;
    }

    public function getData()
    {
        $user = auth()->user();
        $data = $this->model
            // ->where('author_id', $user->id)
            ->where('postable_id', $user->id)
            ->where('postable_type', get_class($user))
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
        $item->tags()->sync($data['tags']);
        return $item;
    }

    public function update($item, $data)
    {
        $this->data = $data;
        $this->beforeUpdate();
        $item->update($this->data);
        $item->tags()->sync($data['tags']);
        return $item;
    }

    public function delete($item)
    {
        $item->tags()->sync([]);
        return $item->delete();
    }

    public function getTeamPosts($team)
    {
        $data = $this->model
            ->where('postable_id', $team->id)
            ->where('postable_type', get_class($team))
            ->latest()
            ->paginate($this->perPage);
        return PostResource::collection($data);
    }

    public function beforeCreate()
    {
        $user = auth()->user();
        $this->data['author_id'] = $user->id;
        $this->data['unique_code'] = Str::random();
        if ($this->publishForTeam) {
            $this->data['postable_id'] = $this->team->id;
            $this->data['postable_type'] = get_class($this->team);
        } else {
            $this->data['postable_id'] = $user->id;
            $this->data['postable_type'] = get_class($user);
        }
    }

    public function beforeUpdate()
    {
        //
    }

    public function publishForTeam($team)
    {
        $this->publishForTeam = true;
        $this->team = $team;
    }
}
