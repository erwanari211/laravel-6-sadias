<?php

namespace Modules\ExamplePermission\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\ExamplePermission\Models\Role;
use Modules\ExamplePermission\Services\RoleService;
use Modules\ExamplePermission\Http\Requests\RoleRequest;
use Modules\ExamplePermission\Services\PermissionService;
use Spatie\Permission\Models\Role as RoleModel;

class RoleController extends Controller
{
    public $service;
    public $data;

    public function __construct()
    {
        $this->service = new RoleService;
        $this->data = [];
        $this->viewLayout = 'examplepermission::layouts.main';

        $this->permissionService = new PermissionService;
    }

    public function index()
    {
        $this->authorize('viewAny', Role::class);
        $roles = $this->service->getData();
        return view('examplepermission::roles.datatables-index')->with([
            'roles' => $roles,
            'viewLayout' => $this->viewLayout,
        ]);
    }

    public function create()
    {
        $this->authorize('create', Role::class);
        $role = new Role;
        return view('examplepermission::roles.create')->with([
            'role' => $role,
            'viewLayout' => $this->viewLayout,
        ]);
    }

    public function store(RoleRequest $request)
    {
        $this->authorize('create', Role::class);
        $data = $request->validated();
        $this->service->create($data);

        $message = __('my_app.messages.data_created');
        flash($message)->success();
        return redirect()->route('example-permission.roles.index');
    }

    public function show(Role $role)
    {
        $this->authorize('view', $role);

        $roleModel = RoleModel::findByName($role->name);
        $rolePermissions = $roleModel->permissions;

        $permissions = $this->permissionService->getAll(['orderBy' => ['name']]);

        return view('examplepermission::roles.show')->with([
            'role' => $role,
            'viewLayout' => $this->viewLayout,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions,
        ]);
    }

    public function edit(Role $role)
    {
        $this->authorize('update', $role);
        return view('examplepermission::roles.edit')->with([
            'role' => $role,
            'viewLayout' => $this->viewLayout,
        ]);
    }

    public function update(RoleRequest $request, Role $role)
    {
        $this->authorize('update', $role);
        $data = $request->validated();
        $this->service->update($role, $data);

        $message = __('my_app.messages.data_updated');
        flash($message)->success();
        return redirect()->route('example-permission.roles.index');
    }

    public function destroy(Role $role)
    {
        $this->authorize('delete', $role);
        $this->service->delete($role);

        $message = __('my_app.messages.data_deleted');
        flash($message)->success();
        return redirect()->route('example-permission.roles.index');
    }
}
