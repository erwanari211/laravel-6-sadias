<?php

namespace Modules\ExampleBlog\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\User;
use Modules\ExampleBlog\Models\Post;

class PostPolicy
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

    public function view(User $user, Post $post)
    {
        return $post->author_id == $user->id;
    }

    public function create(User $user)
    {
        return $user;
    }

    public function update(User $user, Post $post)
    {
        return $post->author_id == $user->id;
    }

    public function delete(User $user, Post $post)
    {
        return $post->author_id == $user->id;
    }

    public function restore(User $user, Post $post)
    {
        return $post->author_id == $user->id;
    }

    public function forceDelete(User $user, Post $post)
    {
        return $post->author_id == $user->id;
    }
}
