<?php

namespace Modules\ExamplePermission\Http\Controllers\Datatables;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use DataTables;
use Modules\ExamplePermission\Services\Datatables\UserService;
use Modules\ExamplePermission\Models\User;
use Modules\ExamplePermission\Http\Requests\UserRequest;

class UserController extends Controller
{
    public $service;
    public $data;

    public function __construct()
    {
        $this->service = new UserService;
    }

    public function index()
    {
        return $this->service->getData();
    }

    public function create()
    {
        //
    }

    public function store(UserRequest $request)
    {
        //
    }

    public function show(User $user)
    {
        //
    }

    public function edit(User $user)
    {
        //
    }

    public function update(UserRequest $request, User $user)
    {
        //
    }

    public function destroy(User $user)
    {
        //
    }
}
