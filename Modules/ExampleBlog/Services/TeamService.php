<?php

namespace Modules\ExampleBlog\Services;

use Modules\ExampleBlog\Models\Team;
use Modules\ExampleBlog\Http\Resources\TeamResource;

class TeamService
{
    public $model;
    public $perPage = 10;
    public $data;

    public function __construct()
    {
        $this->model = new Team;
    }

    public function getData()
    {
        $user = auth()->user();
        $data = $this->model->latest()
            ->where('owner_id', $user->id)
            ->paginate($this->perPage);
        return TeamResource::collection($data);
    }

    public function getItem($id)
    {
        if(is_numeric($id)){
            $item = $this->model->findOrFail($id);
        }
        if($id instanceof Team){
            $item = $id;
        }
        return new TeamResource($item);
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
