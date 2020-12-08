<?php

namespace Modules\ExamplePermission\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\ExamplePermission\Models\User;
use Modules\ExamplePermission\Services\UserService;
use Modules\ExamplePermission\Http\Requests\UserRequest;
use Modules\ExamplePermission\Services\RoleService;
use App\User as UserModel;

class UserController extends Controller
{
    public $service;
    public $data;

    public function __construct()
    {
        $this->service = new UserService;
        $this->data = [];
        $this->viewLayout = 'examplepermission::layouts.main';

        $this->roleService = new RoleService;
    }

    public function index()
    {
        $this->authorize('viewAny', User::class);
        $users = $this->service->getData();
        return view('examplepermission::users.datatables-index')->with([
            'users' => $users,
            'viewLayout' => $this->viewLayout,
        ]);
    }

    public function create()
    {
        $this->authorize('create', User::class);
        $user = new User;
        return view('examplepermission::users.create')->with([
            'user' => $user,
            'viewLayout' => $this->viewLayout,
        ]);
    }

    public function store(UserRequest $request)
    {
        $this->authorize('create', User::class);
        $data = $request->validated();
        $this->service->create($data);

        $message = __('my_app.messages.data_created');
        flash($message)->success();
        return redirect()->route('example-permission.users.index');
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);

        $userAccount = UserModel::find($user->id);
        $userRoles = $userAccount->roles;

        $roles = $this->roleService->getAll(['orderBy' => ['name']]);
        return view('examplepermission::users.show')->with([
            'user' => $user,
            'viewLayout' => $this->viewLayout,
            'userRoles' => $userRoles,
            'roles' => $roles,
        ]);
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('examplepermission::users.edit')->with([
            'user' => $user,
            'viewLayout' => $this->viewLayout,
        ]);
    }

    public function update(UserRequest $request, User $user)
    {
        $this->authorize('update', $user);
        $data = $request->validated();
        $this->service->update($user, $data);

        $message = __('my_app.messages.data_updated');
        flash($message)->success();
        return redirect()->route('example-permission.users.index');
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        $this->service->delete($user);

        $message = __('my_app.messages.data_deleted');
        flash($message)->success();
        return redirect()->route('example-permission.users.index');
    }
}
