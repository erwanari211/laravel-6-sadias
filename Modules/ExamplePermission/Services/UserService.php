<?php

namespace Modules\ExamplePermission\Services;

use Modules\ExamplePermission\Models\User;
use Modules\ExamplePermission\Http\Resources\UserResource;

class UserService
{
    public $model;
    public $perPage = 10;
    public $data;

    public function __construct()
    {
        $this->model = new User;
    }

    public function getData()
    {
        $data = $this->model->latest()->paginate($this->perPage);
        return UserResource::collection($data);
    }

    public function getItem($id)
    {
        if(is_numeric($id)){
            $item = $this->model->findOrFail($id);
        }
        if($id instanceof User){
            $item = $id;
        }
        return new UserResource($item);
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
        if (isset($this->data['password'])) {
            $this->data['password'] = bcrypt($this->data['password']);
        }
    }

    public function beforeUpdate()
    {
        if (isset($this->data['password']) && $this->data['password']) {
            $this->data['password'] = bcrypt($this->data['password']);
        } else {
            unset($this->data['password']);
        }
    }
}
