<?php

namespace Modules\ExamplePermission\Services;

use Modules\ExamplePermission\Models\Permission;
use Modules\ExamplePermission\Http\Resources\PermissionResource;

class PermissionService
{
    public $model;
    public $perPage = 10;
    public $data;

    public function __construct()
    {
        $this->model = new Permission;
    }

    public function getData()
    {
        $data = $this->model->latest()->paginate($this->perPage);
        return PermissionResource::collection($data);
    }

    public function getItem($id)
    {
        if(is_numeric($id)){
            $item = $this->model->findOrFail($id);
        }
        if($id instanceof Permission){
            $item = $id;
        }
        return new PermissionResource($item);
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

    public function getAll($options = [])
    {
        $data = $this->model;
        if (isset($options['orderBy']) && $options['orderBy']) {
            $column = $options['orderBy'][0];
            $dir = $options['orderBy'][1] ?? 'asc';
            $data = $data->orderBy($column, $dir);
        }
        $data = $data->get();
        return $data;
    }
}
