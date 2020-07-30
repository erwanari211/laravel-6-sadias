<?php

namespace Modules\ExampleBlog\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\User;
use Modules\ExampleBlog\Entities\ExampleBlogChannel as Channel;

class ExampleBlogChannelPolicy
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

    public function viewAny(User $user)
    {
        return $user;
    }

    public function view(User $user, Channel $channel)
    {
        return $channel->owner_id == $user->id;
    }

    public function create(User $user)
    {
        return $user;
    }

    public function update(User $user, Channel $channel)
    {
        return $channel->owner_id == $user->id;
    }

    public function delete(User $user, Channel $channel)
    {
        return $channel->owner_id == $user->id;
    }

    public function restore(User $user, Channel $channel)
    {
        return $channel->owner_id == $user->id;
    }

    public function forceDelete(User $user, Channel $channel)
    {
        return $channel->owner_id == $user->id;
    }
}
