<?php

namespace Modules\ExamplePermission\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\ExamplePermission\Models\Permission;
use Modules\ExamplePermission\Services\PermissionService;
use Modules\ExamplePermission\Http\Requests\PermissionRequest;

class PermissionController extends Controller
{
    public $service;
    public $data;

    public function __construct()
    {
        $this->service = new PermissionService;
        $this->data = [];
        $this->viewLayout = 'examplepermission::layouts.main';
    }

    public function index()
    {
        $this->authorize('viewAny', Permission::class);
        $permissions = $this->service->getData();
        return view('examplepermission::permissions.datatables-index')->with([
            'permissions' => $permissions,
            'viewLayout' => $this->viewLayout,
        ]);
    }

    public function create()
    {
        $this->authorize('create', Permission::class);
        $permission = new Permission;
        return view('examplepermission::permissions.create')->with([
            'permission' => $permission,
            'viewLayout' => $this->viewLayout,
        ]);
    }

    public function store(PermissionRequest $request)
    {
        $this->authorize('create', Permission::class);
        $data = $request->validated();
        $this->service->create($data);

        $message = __('my_app.messages.data_created');
        flash($message)->success();
        return redirect()->route('example-permission.permissions.index');
    }

    public function show(Permission $permission)
    {
        $this->authorize('view', $permission);
        return view('examplepermission::permissions.show')->with([
            'permission' => $permission,
            'viewLayout' => $this->viewLayout,
        ]);
    }

    public function edit(Permission $permission)
    {
        $this->authorize('update', $permission);
        return view('examplepermission::permissions.edit')->with([
            'permission' => $permission,
            'viewLayout' => $this->viewLayout,
        ]);
    }

    public function update(PermissionRequest $request, Permission $permission)
    {
        $this->authorize('update', $permission);
        $data = $request->validated();
        $this->service->update($permission, $data);

        $message = __('my_app.messages.data_updated');
        flash($message)->success();
        return redirect()->route('example-permission.permissions.index');
    }

    public function destroy(Permission $permission)
    {
        $this->authorize('delete', $permission);
        $this->service->delete($permission);

        $message = __('my_app.messages.data_deleted');
        flash($message)->success();
        return redirect()->route('example-permission.permissions.index');
    }
}
