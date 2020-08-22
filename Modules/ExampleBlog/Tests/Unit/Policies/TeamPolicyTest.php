<?php

namespace Modules\ExampleBlog\tests\Unit\Policies;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Modules\ExampleBlog\Models\Team;
use Modules\ExampleBlog\Models\TeamMember;
use Modules\ExampleBlog\Models\Post;

class TeamPolicyTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public $itemColumn;
    public $itemUserColumn;
    public $itemAttributes;

    public function setUp(): void
    {
        parent::setUp();

        $this->setBaseRoute('example.blog.backend.channels');
        $this->setBaseModel(Team::class);

        $attributes = [];

        $this->itemUserColumn = 'owner_id';
        $this->itemColumn = 'name';
        $this->itemAttributes = $attributes;
    }

    /** @test */
    public function owner_can_create_team()
    {
        $this->signIn();
        $team = new $this->base_model;
        $this->assertTrue($this->user->can('create', $team));
    }

    /** @test */
    public function owner_can_read_team()
    {
        $this->signIn();
        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $team = $this->newItem($attributes);
        $this->assertTrue($this->user->can('view', $team));
    }

    /** @test */
    public function owner_can_update_team()
    {
        $this->signIn();
        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $team = $this->newItem($attributes);
        $this->assertTrue($this->user->can('update', $team));
    }

    /** @test */
    public function owner_can_delete_team()
    {
        $this->signIn();
        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $team = $this->newItem($attributes);
        $this->assertTrue($this->user->can('delete', $team));
    }

    /** @test */
    public function user_cannot_access_others_team()
    {
        $this->signIn();
        $attributes = $this->itemAttributes;
        $team = $this->newItem($attributes);
        $this->assertFalse($this->user->can('view', $team));
        $this->assertFalse($this->user->can('update', $team));
        $this->assertFalse($this->user->can('delete', $team));
    }

    public function prepareTeamPost($role = 'admin')
    {
        $user = create(User::class);
        $team = create(Team::class, ['owner_id' => $user->id]);
        $member = create(TeamMember::class, [
            'user_id' => $user->id,
            'team_id' => $team->id,
            'role_name' => $role,
        ]);

        return compact('user', 'team', 'member');
    }

    /** @test */
    public function member_can_view_team_posts()
    {
        $prepareData = $this->prepareTeamPost();
        $user = $prepareData['user'];
        $team = $prepareData['team'];

        $this->signIn($user);
        $this->assertTrue($user->can('viewTeamPosts', $team));
    }

    /** @test */
    public function member_can_view_team_post()
    {
        $prepareData = $this->prepareTeamPost();
        $user = $prepareData['user'];
        $team = $prepareData['team'];

        $this->signIn($user);
        $this->assertTrue($user->can('viewTeamPost', $team));
    }

    /** @test */
    public function member_can_create_team_post()
    {
        $prepareData = $this->prepareTeamPost();
        $user = $prepareData['user'];
        $team = $prepareData['team'];

        $this->signIn($user);
        $this->assertTrue($user->can('createTeamPost', $team));
    }

    /** @test */
    public function member_can_edit_their_post()
    {
        $prepareData = $this->prepareTeamPost();
        $user = $prepareData['user'];
        $team = $prepareData['team'];

        $post = create(Post::class, [
            'author_id' => $user->id,
            'postable_id' => $team->id,
            'postable_type' => get_class($team),
            'status' => 'draft',
        ]);

        $this->signIn($user);
        $this->assertTrue($user->can('editTeamPost', [$team, $post]));
    }

    /** @test */
    public function member_can_edit_other_post_if_role_is_admin()
    {
        $prepareData = $this->prepareTeamPost('admin');
        $user = $prepareData['user'];
        $team = $prepareData['team'];

        $otherUser = create(User::class);
        $post = create(Post::class, [
            'author_id' => $otherUser->id,
            'postable_id' => $team->id,
            'postable_type' => get_class($team),
            'status' => 'draft',
        ]);

        $this->signIn($user);
        $this->assertTrue($user->can('editTeamPost', [$team, $post]));
    }

    /** @test */
    public function member_can_edit_other_post_if_role_is_editor()
    {
        $prepareData = $this->prepareTeamPost('editor');
        $user = $prepareData['user'];
        $team = $prepareData['team'];

        $otherUser = create(User::class);
        $post = create(Post::class, [
            'author_id' => $otherUser->id,
            'postable_id' => $team->id,
            'postable_type' => get_class($team),
            'status' => 'draft',
        ]);

        $this->signIn($user);
        $this->assertTrue($user->can('editTeamPost', [$team, $post]));
    }

    /** @test */
    public function member_cannot_edit_other_post_if_role_is_author()
    {
        $prepareData = $this->prepareTeamPost('author');
        $user = $prepareData['user'];
        $team = $prepareData['team'];

        $otherUser = create(User::class);
        $post = create(Post::class, [
            'author_id' => $otherUser->id,
            'postable_id' => $team->id,
            'postable_type' => get_class($team),
            'status' => 'draft',
        ]);

        $this->signIn($user);
        $this->assertTrue($user->can('editTeamPost', [$team, $post]));
    }
}
