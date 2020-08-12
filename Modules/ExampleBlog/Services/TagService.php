<?php

namespace Modules\ExampleBlog\Services;

use Modules\ExampleBlog\Models\Tag;
use Modules\ExampleBlog\Http\Resources\TagResource;

class TagService
{
    public $model;
    public $perPage = 10;
    public $data;
    public $tagOwner = 'user';
    public $team;

    public function __construct()
    {
        $this->model = new Tag;
    }

    public function getData()
    {
        $user = auth()->user();
        $data = $this->model
            ->where('owner_id', $user->id)
            ->latest()->paginate($this->perPage);
        return TagResource::collection($data);
    }

    public function getItem($id)
    {
        if(is_numeric($id)){
            $item = $this->model->findOrFail($id);
        }
        if($id instanceof Tag){
            $item = $id;
        }
        return new TagResource($item);
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
        $this->data['owner_id'] = auth()->user()->id;

        if ($this->tagOwner == 'user') {
            $this->data['ownerable_id'] = auth()->user()->id;
            $this->data['ownerable_type'] = get_class(auth()->user());
        }

        if ($this->tagOwner == 'team') {
            $this->data['ownerable_id'] = $this->team->id;
            $this->data['ownerable_type'] = get_class($this->team);
        }
    }

    public function beforeUpdate()
    {
        //
    }

    public function publishForTeam($team)
    {
        $this->tagOwner = 'team';
        $this->team = $team;
    }
}
