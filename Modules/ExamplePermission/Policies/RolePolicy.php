<?php

namespace Modules\ExamplePermission\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\User;
use Modules\ExamplePermission\Models\Role;

class RolePolicy
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

    public function view(User $user, Role $role)
    {
        return $user;
        // return $role->user_id == $user->id;
    }

    public function create(User $user)
    {
        return $user;
    }

    public function update(User $user, Role $role)
    {
        return $user;
        // return $role->user_id == $user->id;
    }

    public function delete(User $user, Role $role)
    {
        return $user;
        // return $role->user_id == $user->id;
    }

    public function restore(User $user, Role $role)
    {
        return $role->user_id == $user->id;
    }

    public function forceDelete(User $user, Role $role)
    {
        return $role->user_id == $user->id;
    }
}
