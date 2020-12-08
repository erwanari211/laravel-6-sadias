<?php

namespace Modules\ExamplePermission\Http\Controllers\Datatables;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use DataTables;
use Modules\ExamplePermission\Services\Datatables\PermissionService;
use Modules\ExamplePermission\Models\Permission;
use Modules\ExamplePermission\Http\Requests\PermissionRequest;

class PermissionController extends Controller
{
    public $service;
    public $data;

    public function __construct()
    {
        $this->service = new PermissionService;
    }

    public function index()
    {
        return $this->service->getData();
    }

    public function create()
    {
        //
    }

    public function store(PermissionRequest $request)
    {
        //
    }

    public function show(Permission $permission)
    {
        //
    }

    public function edit(Permission $permission)
    {
        //
    }

    public function update(PermissionRequest $request, Permission $permission)
    {
        //
    }

    public function destroy(Permission $permission)
    {
        //
    }
}
