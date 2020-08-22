<?php

namespace Modules\ExampleBlog\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\User;
use Modules\ExampleBlog\Models\Team;
use Modules\ExampleBlog\Models\Post;

class TeamPolicy
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

    public function view(User $user, Team $team)
    {
        $isMember = $team->teamMembers()->where('user_id', $user->id)->exists();
        if ($isMember) {
            return true;
        }
        return $team->owner_id == $user->id;
    }

    public function create(User $user)
    {
        return $user;
    }

    public function update(User $user, Team $team)
    {
        return $team->owner_id == $user->id;
    }

    public function delete(User $user, Team $team)
    {
        return $team->owner_id == $user->id;
    }

    public function restore(User $user, Team $team)
    {
        return $team->owner_id == $user->id;
    }

    public function forceDelete(User $user, Team $team)
    {
        return $team->owner_id == $user->id;
    }

    public function viewTeamPosts(User $user, Team $team)
    {
        $isMember = $team->teamMembers()->where('user_id', $user->id)->exists();
        if ($isMember) {
            return true;
        }
        return $team->owner_id == $user->id;
    }

    public function viewTeamPost(User $user, Team $team)
    {
        $isMember = $team->teamMembers()->where('user_id', $user->id)->exists();
        if ($isMember) {
            return true;
        }
        return $team->owner_id == $user->id;
    }

    public function createTeamPost(User $user, Team $team)
    {
        $isMember = $team->teamMembers()->where('user_id', $user->id)->exists();
        if ($isMember) {
            return true;
        }
        return $team->owner_id == $user->id;
    }

    public function editTeamPost(User $user, Team $team, Post $post)
    {
        $ownedByTeam = $post->postable->id == $team->id;
        if(!$ownedByTeam) {
            return false;
        }

        $isMember = $team->teamMembers()->where('user_id', $user->id)->exists();
        if (!$isMember) {
            return false;
        }

        if ($post->author_id == $user->id) {
            return true;
        }

        $member = $team->teamMembers()->where('user_id', $user->id)->first();
        $allowedToEditOtherPost = ['admin', 'editor'];
        if (in_array($member->role_name, $allowedToEditOtherPost) ) {
            return true;
        }

        return $team->owner_id == $user->id;
    }

    public function deleteTeamPost(User $user, Team $team, Post $post)
    {
        $ownedByTeam = $post->postable->id == $team->id;
        if(!$ownedByTeam) {
            return false;
        }

        $isMember = $team->teamMembers()->where('user_id', $user->id)->exists();
        if (!$isMember) {
            return false;
        }

        if ($post->author_id == $user->id) {
            return true;
        }

        $member = $team->teamMembers()->where('user_id', $user->id)->first();
        $allowedToEditOtherPost = ['admin', 'editor'];
        if (in_array($member->role_name, $allowedToEditOtherPost) ) {
            return true;
        }

        return $team->owner_id == $user->id;
    }
}
