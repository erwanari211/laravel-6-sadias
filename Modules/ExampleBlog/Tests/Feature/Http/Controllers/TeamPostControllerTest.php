<?php

namespace Modules\ExampleBlog\tests\Feature\Http\Controllers;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Str;
use Modules\ExampleBlog\Models\Post;
use Modules\ExampleBlog\Models\Team;
use Modules\ExampleBlog\Models\TeamMember;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeamPostControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public $itemColumn;
    public $itemUserColumn;
    public $itemAttributes;
    public $team;

    public function setUp(): void
    {
        parent::setUp();

        $this->setBaseRoute('example-blog.teams.posts');
        $this->setBaseModel(Post::class);

        $user = create(User::class);
        $team = create(Team::class, ['owner_id' => $user->id]);
        $attributes = [
            'postable_id' => $team->id,
            'postable_type' => get_class($team),
            'status' => 'draft',
        ];
        $teamMember = create(TeamMember::class, [
            'user_id' => $user->id,
            'team_id' => $team->id,
            'role_name' => 'admin',
        ]);

        $this->itemUserColumn = 'author_id';
        $this->itemColumn = 'title';
        $this->itemAttributes = $attributes;

        $this->user = $user;
        $this->team = $team;
        $this->teamMember = $teamMember;
    }

    /** @test */
    public function guest_cannot_read_posts()
    {
        $post = create($this->base_model, $this->itemAttributes);

        $response = $this->readAllItems([$this->team->id]);

        $response->assertStatus(302);
        $response->assertRedirect('login');
        $response->assertDontSee($post->{$this->itemColumn});
    }

    public function authenticated_user_can_read_all_their_posts()
    {
        $this->signIn($this->user);
        $this->itemAttributes[$this->itemUserColumn] = $this->user->id;
        $post = $this->newItem($this->itemAttributes);

        $response = $this->readAllItems([$this->team->id]);

        $response->assertStatus(200);
        $response->assertViewIs('exampleblog::team-posts.index');
		$response->assertViewHas('posts');
        $response->assertSee($post->{$this->itemColumn});
    }


    /** @test */
    public function authenticated_user_can_read_all_team_posts()
    {
        $this->signIn($this->user);
        $otherMember = create(User::class);
        $this->itemAttributes[$this->itemUserColumn] = $otherMember->id;
        $post = $this->newItem($this->itemAttributes);

        $response = $this->readAllItems([$this->team->id]);

        $response->assertStatus(200);
        $response->assertViewIs('exampleblog::team-posts.index');
		$response->assertViewHas('posts');
        $response->assertSee($post->{$this->itemColumn});
    }


    /** @test */
    public function authenticated_user_cannot_read_other_team_posts()
    {
        $this->signIn($this->user);
        $team = create(Team::class);
        $attributes = $this->itemAttributes;
        $attributes['postable_id'] = $team->id;
        $post = $this->newItem($attributes);

        $response = $this->readAllItems([$this->team->id]);

        $response->assertStatus(200);
        $response->assertDontSee($post->{$this->itemColumn});
    }

    /** @test */
    public function guest_cannot_create_a_new_post()
    {
        $response = $this->visitCreatePage([$this->team->id]);

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    /** @test */
    public function authenticated_user_can_create_a_new_post()
    {
        $this->signIn($this->user);

        $response = $this->visitCreatePage([$this->team->id]);

        $response->assertStatus(200);
        $response->assertViewIs('exampleblog::team-posts.create');
		$response->assertViewHas('post');

        $data = raw($this->base_model, $this->itemAttributes);
        $data['status'] = 'draft';
        unset($data[$this->itemUserColumn]);
        $response = $this->createItem($data, [$this->team->id]);

        $attributes = collect($data)->only(['title', 'slug', 'content']);
        $attributes['slug'] = Str::slug($attributes['slug']);
        $model = new $this->base_model;
        $this->assertEquals(1, $model->all()->count());
        $this->assertDatabaseHas($model->getTable(), $attributes->toArray());

        // $response->assertSessionHas(['successMessage']);
        // $response->assertSessionHas('successMessage', __('my_app.messages.data_created'));

        $response->assertSessionHas(['flash_notification']);
        $session = session('flash_notification')[0];
        $this->assertEquals($session['message'], __('my_app.messages.data_created'));
        $response->assertStatus(302);
    }

    /** @test */
    public function guest_cannot_view_a_post()
    {
        $post = $this->newItem($this->itemAttributes);

        $response = $this->readItem([$this->team->id, $post->id]);

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    /** @test */
    public function authenticated_user_can_view_a_post()
    {
        $this->signIn($this->user);
        $this->itemAttributes[$this->itemUserColumn] = $this->user->id;
        $post = $this->newItem($this->itemAttributes);
        dump($post);

        $response = $this->readItem([$this->team->id, $post->id]);

        $response->assertStatus(200);
        $response->assertViewIs('exampleblog::team-posts.show');
		$response->assertViewHas('post');
    }

    /** @test */
    public function authenticated_user_can_view_team_post()
    {
        $this->signIn($this->user);
        $otherUser = create(User::class);
        $this->itemAttributes[$this->itemUserColumn] = $otherUser->id;
        $post = $this->newItem($this->itemAttributes);

        $response = $this->readItem([$this->team->id, $post->id]);

        $response->assertStatus(200);
        $response->assertViewIs('exampleblog::team-posts.show');
		$response->assertViewHas('post');
    }

    /** @test */
    public function authenticated_user_cannot_view_others_team_post()
    {
        $this->signIn();
        $otherTeam = create(Team::class);
        $this->itemAttributes['postable_id'] = $otherTeam->id;
        $post = $this->newItem($this->itemAttributes);

        $response = $this->readItem([$otherTeam->id, $post->id]);

        $response->assertStatus(403);
    }

    /** @test */
    public function guest_cannot_update_the_post()
    {
        $post = create($this->base_model, $this->itemAttributes);

        $response = $this->visitEditPage([$this->team->id, $post->id]);

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    /** @test */
    public function authorized_user_can_update_the_post()
    {
        $this->signIn($this->user);

        $this->itemAttributes[$this->itemUserColumn] = $this->user->id;
        $post = $this->newItem($this->itemAttributes);

        $oldName = $post->{$this->itemColumn};
        $newName = $this->faker->sentence;
        $post->{$this->itemColumn} = $newName;

        $response = $this->visitEditPage([$this->team->id, $post->id]);

        $response->assertStatus(200);
        $response->assertViewIs('exampleblog::team-posts.edit');
		$response->assertViewHas('post');

        $response = $this->updateItem([$this->team->id, $post->id], $post->toArray());

        $model = new $this->base_model;
        $this->assertEquals(1, $model->all()->count());
        $this->assertDatabaseHas($model->getTable(), [
            $this->itemUserColumn => $this->user->id,
            $this->itemColumn => $newName,
        ]);
        $this->assertDatabaseMissing($model->getTable(), [
            $this->itemUserColumn => $this->user->id,
            $this->itemColumn => $oldName,
        ]);

        // $response->assertSessionHas(['successMessage']);
        // $response->assertSessionHas('successMessage', __('my_app.messages.data_updated'));

        $response->assertSessionHas(['flash_notification']);
        $session = session('flash_notification')[0];
        $this->assertEquals($session['message'], __('my_app.messages.data_updated'));
        $response->assertStatus(302);
    }

    /** @test */
    public function authorized_user_can_update_other_post_if_role_is_admin()
    {
        $this->signIn($this->user);

        $this->itemAttributes[$this->itemUserColumn] = $this->user->id;
        $post = $this->newItem($this->itemAttributes);

        $admin = create(User::class);
        $member = create(TeamMember::class, [
            'user_id' => $admin->id,
            'team_id' => $this->team->id,
            'role_name' => 'admin',
        ]);
        $this->signIn($admin);

        $response = $this->updateItem([$this->team->id, $post->id], $post->toArray());

        $response->assertSessionHas(['flash_notification']);
        $session = session('flash_notification')[0];
        $this->assertEquals($session['message'], __('my_app.messages.data_updated'));
        $response->assertStatus(302);
    }

    /** @test */
    public function authorized_user_can_update_other_post_if_role_is_editor()
    {
        $this->signIn($this->user);

        $this->itemAttributes[$this->itemUserColumn] = $this->user->id;
        $post = $this->newItem($this->itemAttributes);

        $editor = create(User::class);
        $member = create(TeamMember::class, [
            'user_id' => $editor->id,
            'team_id' => $this->team->id,
            'role_name' => 'editor',
        ]);
        $this->signIn($editor);

        $response = $this->updateItem([$this->team->id, $post->id], $post->toArray());

        $response->assertSessionHas(['flash_notification']);
        $session = session('flash_notification')[0];
        $this->assertEquals($session['message'], __('my_app.messages.data_updated'));
        $response->assertStatus(302);
    }

    /** @test */
    public function authorized_user_cannot_update_other_post_if_role_is_author()
    {
        $this->signIn($this->user);

        $this->itemAttributes[$this->itemUserColumn] = $this->user->id;
        $post = $this->newItem($this->itemAttributes);

        $editor = create(User::class);
        $member = create(TeamMember::class, [
            'user_id' => $editor->id,
            'team_id' => $this->team->id,
            'role_name' => 'author',
        ]);
        $this->signIn($editor);

        $response = $this->updateItem([$this->team->id, $post->id], $post->toArray());

        $response->assertStatus(403);
    }

    /** @test */
    public function authorized_user_cannot_update_other_team_post()
    {
        $this->signIn($this->user);
        $otherTeam = create(Team::class);
        $this->itemAttributes['postable_id'] = $otherTeam->id;
        $post = $this->newItem($this->itemAttributes);
        $newName = $this->faker->sentence;
        $post->{$this->itemColumn} = $newName;

        $response = $this->updateItem([$otherTeam->id, $post->id], $post->toArray());

        $response->assertStatus(403);
    }

    /** @test */
    public function authorized_user_can_delete_the_post()
    {
        $this->signIn($this->user);
        $this->itemAttributes[$this->itemUserColumn] = $this->user->id;
        $post = $this->newItem($this->itemAttributes);

        $response = $this->deleteItem([$this->team->id, $post->id]);

        $model = new $this->base_model;
        $this->assertEquals(0, $model->all()->count());
        $this->assertDatabaseMissing($model->getTable(), [
            $this->itemUserColumn => $this->user->id,
            $this->itemColumn => $post->{$this->itemColumn},
        ]);

        // $response->assertSessionHas(['successMessage']);
        // $response->assertSessionHas('successMessage', __('my_app.messages.data_deleted'));

        $response->assertSessionHas(['flash_notification']);
        $session = session('flash_notification')[0];
        $this->assertEquals($session['message'], __('my_app.messages.data_deleted'));
        $response->assertStatus(302);
    }

    /** @test */
    public function authorized_user_cannot_delete_other_post()
    {
        $this->signIn($this->user);
        $this->itemAttributes[$this->itemUserColumn] = $this->user->id;
        $post = $this->newItem($this->itemAttributes);

        $otherUser = create(User::class);
        $teamMember = create(TeamMember::class, [
            'user_id' => $otherUser->id,
            'team_id' => $this->team->id,
            'role_name' => 'author',
        ]);
        $this->signIn($otherUser);
        $response = $this->deleteItem([$this->team->id, $post->id]);
        $response->assertStatus(403);
    }

    /** @test */
    public function authorized_user_can_delete_other_post_if_role_is_admin()
    {
        $this->signIn($this->user);
        $this->itemAttributes[$this->itemUserColumn] = $this->user->id;
        $post = $this->newItem($this->itemAttributes);

        $otherUser = create(User::class);
        $teamMember = create(TeamMember::class, [
            'user_id' => $otherUser->id,
            'team_id' => $this->team->id,
            'role_name' => 'admin',
        ]);
        $this->signIn($otherUser);
        $response = $this->deleteItem([$this->team->id, $post->id]);
        $response->assertStatus(302);
    }

    /** @test */
    public function authorized_user_can_delete_other_post_if_role_is_editor()
    {
        $this->signIn($this->user);
        $this->itemAttributes[$this->itemUserColumn] = $this->user->id;
        $post = $this->newItem($this->itemAttributes);

        $otherUser = create(User::class);
        $teamMember = create(TeamMember::class, [
            'user_id' => $otherUser->id,
            'team_id' => $this->team->id,
            'role_name' => 'editor',
        ]);
        $this->signIn($otherUser);
        $response = $this->deleteItem([$this->team->id, $post->id]);
        $response->assertStatus(302);
    }

    /** @test */
    public function authorized_user_cannot_delete_other_team_post()
    {
        $this->signIn();
        $otherTeam = create(Team::class);
        $this->itemAttributes['postable_id'] = $otherTeam->id;
        $post = $this->newItem($this->itemAttributes);

        $response = $this->deleteItem([$otherTeam->id, $post->id]);

        $model = new $this->base_model;
        $this->assertEquals(1, $model->all()->count());
        $this->assertDatabaseHas($model->getTable(), [
            $this->itemColumn => $post->{$this->itemColumn},
        ]);
        $response->assertStatus(403);
    }
}
