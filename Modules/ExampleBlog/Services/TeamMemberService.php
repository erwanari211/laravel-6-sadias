<?php

namespace Modules\ExampleBlog\Services;

use App\User;
use Modules\ExampleBlog\Models\TeamMember;
use Modules\ExampleBlog\Http\Resources\TeamMemberResource;

class TeamMemberService
{
    public $model;
    public $perPage = 10;
    public $data;

    public function __construct($team)
    {
        $this->model = new TeamMember;
        $this->team = $team;
    }

    public function getData()
    {
        $data = $this->model
            ->with('user')
            ->where('team_id', $this->team->id)
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
        $isExists = $this->checkMemberExists();
        if ($isExists) {
            return false;
        }

        $this->beforeCreate();
        $item = $this->model;
        $item = $item->create($this->data);
        return $item;
    }

    public function update($item, $data)
    {
        $this->data = $data;
        $this->data['email'] = $item->user->email;
        $isOwner = $this->checkIsOwner();
        if ($isOwner) {
            return false;
        }
        $this->beforeUpdate();
        $item->update($this->data);
        return $item;
    }

    public function delete($item)
    {
        $this->data['email'] = $item->user->email;
        $isOwner = $this->checkIsOwner();
        if ($isOwner) {
            return false;
        }
        return $item->delete();
    }

    public function beforeCreate()
    {
        $this->data['team_id'] = $this->team->id;

        $email = $this->data['email'];
        $user = User::where('email', $email)->first();
        $this->data['user_id'] = $user->id;
    }

    public function beforeUpdate()
    {
        //
    }

    public function checkMemberExists()
    {
        $team = $this->team;
        $email = $this->data['email'];
        $user = User::where('email', $email)->first();
        $isExists = $this->model->where([
            'team_id' => $team->id,
            'user_id' => $user->id
        ])->exists();
        return $isExists;
    }

    public function checkIsOwner()
    {
        $team = $this->team;
        $email = $this->data['email'];
        $user = User::where('email', $email)->first();
        $isOwner = $team->owner_id == $user->id;
        return $isOwner;
    }
}
