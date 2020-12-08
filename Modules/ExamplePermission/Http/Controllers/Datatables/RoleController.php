<?php

namespace Modules\ExamplePermission\Http\Controllers\Datatables;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use DataTables;
use Modules\ExamplePermission\Services\Datatables\RoleService;
use Modules\ExamplePermission\Models\Role;
use Modules\ExamplePermission\Http\Requests\RoleRequest;

class RoleController extends Controller
{
    public $service;
    public $data;

    public function __construct()
    {
        $this->service = new RoleService;
    }

    public function index()
    {
        return $this->service->getData();
    }

    public function create()
    {
        //
    }

    public function store(RoleRequest $request)
    {
        //
    }

    public function show(Role $role)
    {
        //
    }

    public function edit(Role $role)
    {
        //
    }

    public function update(RoleRequest $request, Role $role)
    {
        //
    }

    public function destroy(Role $role)
    {
        //
    }
}
