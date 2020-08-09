<?php

namespace Modules\ExampleBlog\Services;

use Modules\ExampleBlog\Models\TeamMember;
use Modules\ExampleBlog\Http\Resources\TeamMemberResource;

class TeamMemberService
{
    public $model;
    public $perPage = 10;
    public $data;

    public function __construct()
    {
        $this->model = new TeamMember;
    }

    public function getData()
    {
        $user = auth()->user();
        $data = $this->model
            ->where('user_id', $user->id)
            ->latest()->paginate($this->perPage);
        return TeamMemberResource::collection($data);
    }

    public function getItem($id)
    {
        if(is_numeric($id)){
            $item = $this->model->findOrFail($id);
        }
        if($id instanceof TeamMember){
            $item = $id;
        }
        return new TeamMemberResource($item);
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
