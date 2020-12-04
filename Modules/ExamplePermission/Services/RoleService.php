<?php

namespace Modules\ExamplePermission\Services;

use Modules\ExamplePermission\Models\Role;
use Modules\ExamplePermission\Http\Resources\RoleResource;

class RoleService
{
    public $model;
    public $perPage = 10;
    public $data;

    public function __construct()
    {
        $this->model = new Role;
    }

    public function getData()
    {
        $data = $this->model->latest()->paginate($this->perPage);
        return RoleResource::collection($data);
    }

    public function getItem($id)
    {
        if(is_numeric($id)){
            $item = $this->model->findOrFail($id);
        }
        if($id instanceof Role){
            $item = $id;
        }
        return new RoleResource($item);
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
