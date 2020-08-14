<?php

namespace Modules\ExampleBlog\tests\Feature\Http\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Modules\ExampleBlog\Models\TeamMember;
use Modules\ExampleBlog\Models\Team;

class TeamMemberControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public $itemColumn;
    public $itemUserColumn;
    public $itemAttributes;

    public function setUp(): void
    {
        parent::setUp();

        $this->setBaseRoute('example-blog.team-members');
        $this->setBaseModel(TeamMember::class);

        $user = create(User::class);
        $team = create(Team::class, ['owner_id' => $user->id]);
        $teamMember = create(TeamMember::class, [
            'user_id' => $user->id,
            'team_id' => $team->id,
            'role_name' => 'admin',
        ]);

        $attributes = [
            'team_id' => $team->id,
        ];

        $this->itemUserColumn = 'user_id';
        $this->itemColumn = 'role_name';
        $this->itemAttributes = $attributes;

        $this->user = $user;
        $this->team = $team;
        $this->teamMember = $teamMember;
    }

    /** @test */
    public function guest_cannot_read_teamMembers()
    {
        $teamMember = create($this->base_model, $this->itemAttributes);

        $response = $this->readAllItems($this->team->id);

        $response->assertStatus(302);
        $response->assertRedirect('login');
        $response->assertDontSee($teamMember->{$this->itemColumn});
    }


    /** @test */
    public function authenticated_user_can_read_all_their_teamMembers()
    {
        $this->signIn();
        $this->itemAttributes[$this->itemUserColumn] = $this->user->id;
        $teamMember = $this->newItem($this->itemAttributes);

        $response = $this->readAllItems($this->team->id);

        $response->assertStatus(200);
        $response->assertViewIs('exampleblog::team-members.index');
		$response->assertViewHas('teamMembers');
        $response->assertSee($teamMember->{$this->itemColumn});
    }

    /** @test */
    public function authenticated_user_cannot_read_others_teamMembers()
    {
        $this->signIn();
        $otherTeam = create(Team::class);
        $this->itemAttributes['team_id'] = $otherTeam->id;
        $teamMember = $this->newItem($this->itemAttributes);

        $response = $this->readAllItems($this->team->id);

        $response->assertStatus(200);
        $response->assertDontSee($teamMember->{$this->itemColumn});
    }

    /** @test */
    public function guest_cannot_create_a_new_teamMember()
    {
        $response = $this->visitCreatePage($this->team->id);

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    /** @test */
    public function authenticated_user_can_create_a_new_teamMember()
    {
        $this->signIn();

        $response = $this->visitCreatePage($this->team->id);

        $response->assertStatus(200);
        $response->assertViewIs('exampleblog::team-members.create');
		$response->assertViewHas('teamMember');

        $data = raw($this->base_model, $this->itemAttributes);
        $newUser = create(User::class);
        $data['role_name'] = 'editor';
        $data['email'] = $newUser->email;
        unset($data[$this->itemUserColumn]);
        $response = $this->createItem($data, $this->team->id);

        unset($data['email']);
        $model = new $this->base_model;
        $this->assertEquals(2, $model->all()->count());
        $this->assertDatabaseHas($model->getTable(), $data);

        // $response->assertSessionHas(['successMessage']);
        // $response->assertSessionHas('successMessage', __('my_app.messages.data_created'));

        $response->assertSessionHas(['flash_notification']);
        $session = session('flash_notification')[0];
        $this->assertEquals($session['message'], __('my_app.messages.data_created'));
        $response->assertStatus(302);
    }

    /** @test */
    public function guest_cannot_view_a_teamMember()
    {
        $teamMember = $this->newItem($this->itemAttributes);

        $response = $this->readItem([$this->team->id, $teamMember->id]);

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    /** @test */
    public function authenticated_user_can_view_a_teamMember()
    {
        $this->signIn($this->user);
        $this->itemAttributes[$this->itemUserColumn] = $this->user->id;
        $teamMember = $this->newItem($this->itemAttributes);

        $response = $this->readItem([$this->team->id, $teamMember->id]);

        $response->assertStatus(200);
        $response->assertViewIs('exampleblog::team-members.show');
		$response->assertViewHas('teamMember');
    }

    /** @test */
    public function authenticated_user_cannot_view_others_teamMember()
    {
        $this->signIn($this->user);
        $otherTeam = create(Team::class);
        $this->itemAttributes['team_id'] = $otherTeam->id;
        $teamMember = $this->newItem($this->itemAttributes);

        $response = $this->readItem([$otherTeam->id, $teamMember->id]);

        $response->assertStatus(403);
    }

    /** @test */
    public function guest_cannot_update_the_teamMember()
    {
        $teamMember = create($this->base_model, $this->itemAttributes);

        $response = $this->visitEditPage([$this->team->id, $teamMember->id]);

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    /** @test */
    public function authorized_user_can_update_the_teamMember()
    {
        $this->signIn($this->user);

        $newUser = create(User::class);
        $this->itemAttributes[$this->itemUserColumn] = $newUser->id;
        $teamMember = $this->newItem($this->itemAttributes);

        $oldName = $teamMember->{$this->itemColumn};
        $newName = 'editor';
        $teamMember->{$this->itemColumn} = $newName;

        $response = $this->visitEditPage([$this->team->id, $teamMember->id]);

        $response->assertStatus(200);
        $response->assertViewIs('exampleblog::team-members.edit');
		$response->assertViewHas('teamMember');

        $response = $this->updateItem([$this->team->id, $teamMember->id], $teamMember->toArray());

        $model = new $this->base_model;
        $this->assertEquals(2, $model->all()->count());
        $this->assertDatabaseHas($model->getTable(), [
            $this->itemUserColumn => $newUser->id,
            $this->itemColumn => $newName,
        ]);
        $this->assertDatabaseMissing($model->getTable(), [
            $this->itemUserColumn => $newUser->id,
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
    public function authorized_user_cannot_update_others_teamMember()
    {
        $this->signIn();
        $otherTeam = create(Team::class);
        $this->itemAttributes[$this->itemUserColumn] = $otherTeam->id;
        $teamMember = $this->newItem($this->itemAttributes);
        $newName = $this->faker->sentence;
        $teamMember->{$this->itemColumn} = $newName;

        $response = $this->updateItem([$otherTeam->id, $teamMember->id], $teamMember->toArray());

        $response->assertStatus(403);
    }

    /** @test */
    public function authorized_user_can_delete_the_teamMember()
    {
        $this->signIn($this->user);
        $newUser = create(User::class);
        $this->itemAttributes[$this->itemUserColumn] = $newUser->id;
        $teamMember = $this->newItem($this->itemAttributes);

        $response = $this->deleteItem([$this->team->id, $teamMember->id]);

        $model = new $this->base_model;
        $this->assertEquals(1, $model->all()->count());
        $this->assertNotEquals(2, $model->all()->count());
        $this->assertDatabaseMissing($model->getTable(), [
            $this->itemUserColumn => $this->user->id,
            $this->itemColumn => $teamMember->{$this->itemColumn},
        ]);

        // $response->assertSessionHas(['successMessage']);
        // $response->assertSessionHas('successMessage', __('my_app.messages.data_deleted'));

        $response->assertSessionHas(['flash_notification']);
        $session = session('flash_notification')[0];
        $this->assertEquals($session['message'], __('my_app.messages.data_deleted'));
        $response->assertStatus(302);
    }

    /** @test */
    public function authorized_user_cannot_delete_others_teamMember()
    {
        $this->signIn();
        $otherTeam = create(Team::class);
        $this->itemAttributes['team_id'] = $otherTeam->id;
        $this->itemAttributes['role_name'] = 'admin';
        $teamMember = $this->newItem($this->itemAttributes);

        $response = $this->deleteItem([$otherTeam->id, $teamMember->id]);

        $model = new $this->base_model;
        $this->assertEquals(2, $model->all()->count());
        $this->assertDatabaseHas($model->getTable(), [
            'team_id' => $otherTeam->id,
            $this->itemColumn => 'admin',
        ]);
        $response->assertStatus(403);
    }
}
