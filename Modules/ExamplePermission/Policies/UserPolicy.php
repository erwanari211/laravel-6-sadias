<?php

namespace Modules\ExamplePermission\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\User;
use Modules\ExamplePermission\Models\User as UserModel;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function before($user, $ability)
    {
        //
    }


    public function viewAny(User $user)
    {
        return $user;
    }

    public function view(User $user, UserModel $userModel)
    {
        return $user;
        // return $userModel->user_id == $user->id;
    }

    public function create(User $user)
    {
        return $user;
    }

    public function update(User $user, UserModel $userModel)
    {
        return $user;
        // return $userModel->user_id == $user->id;
    }

    public function delete(User $user, UserModel $userModel)
    {
        return $user;
        // return $userModel->user_id == $user->id;
    }

    public function restore(User $user, UserModel $userModel)
    {
        return $userModel->user_id == $user->id;
    }

    public function forceDelete(User $user, UserModel $userModel)
    {
        return $userModel->user_id == $user->id;
    }
}
