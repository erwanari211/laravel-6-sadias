<?php

namespace Modules\ExampleBlog\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\User;
use Modules\ExampleBlog\Models\Comment;

class CommentPolicy
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

    public function view(User $user, Comment $comment)
    {
        return $comment->author_id == $user->id;
    }

    public function create(User $user)
    {
        return $user;
    }

    public function update(User $user, Comment $comment)
    {
        return $comment->author_id == $user->id;
    }

    public function delete(User $user, Comment $comment)
    {
        return $comment->author_id == $user->id;
    }

    public function restore(User $user, Comment $comment)
    {
        return $comment->author_id == $user->id;
    }

    public function forceDelete(User $user, Comment $comment)
    {
        return $comment->author_id == $user->id;
    }
}
