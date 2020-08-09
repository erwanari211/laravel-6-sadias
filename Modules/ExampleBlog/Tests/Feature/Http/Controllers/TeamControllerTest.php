<?php

namespace Modules\ExampleBlog\tests\Feature\Http\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Modules\ExampleBlog\Models\Team;

class TeamControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public $itemColumn;
    public $itemUserColumn;
    public $itemAttributes;

    public function setUp(): void
    {
        parent::setUp();

        $this->setBaseRoute('example-blog.teams');
        $this->setBaseModel(Team::class);

        $attributes = [];

        $this->itemUserColumn = 'owner_id';
        $this->itemColumn = 'name';
        $this->itemAttributes = $attributes;
    }

    /** @test */
    public function guest_cannot_read_teams()
    {
        $team = create($this->base_model, $this->itemAttributes);

        $response = $this->readAllItems();

        $response->assertStatus(302);
        $response->assertRedirect('login');
        $response->assertDontSee($team->{$this->itemColumn});
    }


    /** @test */
    public function authenticated_user_can_read_all_their_teams()
    {
        $this->signIn();
        $this->itemAttributes[$this->itemUserColumn] = $this->user->id;
        $team = $this->newItem($this->itemAttributes);

        $response = $this->readAllItems();

        $response->assertStatus(200);
        $response->assertViewIs('exampleblog::teams.index');
		$response->assertViewHas('teams');
        $response->assertSee($team->{$this->itemColumn});
    }

    /** @test */
    public function authenticated_user_cannot_read_others_teams()
    {
        $this->signIn();
        $otherUser = create(User::class);
        $this->itemAttributes[$this->itemUserColumn] = $otherUser->id;
        $team = $this->newItem($this->itemAttributes);

        $response = $this->readAllItems();

        $response->assertStatus(200);
        $response->assertDontSee($team->{$this->itemColumn});
    }

    /** @test */
    public function guest_cannot_create_a_new_team()
    {
        $response = $this->visitCreatePage();

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    /** @test */
    public function authenticated_user_can_create_a_new_team()
    {
        $this->signIn();

        $response = $this->visitCreatePage();

        $response->assertStatus(200);
        $response->assertViewIs('exampleblog::teams.create');
		$response->assertViewHas('team');

        $data = raw($this->base_model, $this->itemAttributes);
        unset($data[$this->itemUserColumn]);
        $response = $this->createItem($data);

        $model = new $this->base_model;
        $this->assertEquals(1, $model->all()->count());
        $this->assertDatabaseHas($model->getTable(), $data);

        // $response->assertSessionHas(['successMessage']);
        // $response->assertSessionHas('successMessage', __('my_app.messages.data_created'));

        $response->assertSessionHas(['flash_notification']);
        $session = session('flash_notification')[0];
        $this->assertEquals($session['message'], __('my_app.messages.data_created'));
        $response->assertStatus(302);
    }

    /** @test */
    public function guest_cannot_view_a_team()
    {
        $team = $this->newItem($this->itemAttributes);

        $response = $this->readItem($team->id);

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    /** @test */
    public function authenticated_user_can_view_a_team()
    {
        $this->signIn();
        $this->itemAttributes[$this->itemUserColumn] = $this->user->id;
        $team = $this->newItem($this->itemAttributes);

        $response = $this->readItem($team->id);

        $response->assertStatus(200);
        $response->assertViewIs('exampleblog::teams.show');
		$response->assertViewHas('team');
    }

    /** @test */
    public function authenticated_user_cannot_view_others_team()
    {
        $this->signIn();
        $otherUser = create(User::class);
        $this->itemAttributes[$this->itemUserColumn] = $otherUser->id;
        $team = $this->newItem($this->itemAttributes);

        $response = $this->readItem($team->id);

        $response->assertStatus(403);
    }

    /** @test */
    public function guest_cannot_update_the_team()
    {
        $team = create($this->base_model, $this->itemAttributes);

        $response = $this->visitEditPage($team->id);

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    /** @test */
    public function authorized_user_can_update_the_team()
    {
        $this->signIn();

        $this->itemAttributes[$this->itemUserColumn] = $this->user->id;
        $team = $this->newItem($this->itemAttributes);
        $oldName = $team->{$this->itemColumn};
        $newName = $this->faker->sentence;
        $team->{$this->itemColumn} = $newName;

        $response = $this->visitEditPage($team->id);

        $response->assertStatus(200);
        $response->assertViewIs('exampleblog::teams.edit');
		$response->assertViewHas('team');

        $response = $this->updateItem($team->id, $team->toArray());

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
    public function authorized_user_cannot_update_others_team()
    {
        $this->signIn();
        $otherUser = create(User::class);
        $this->itemAttributes[$this->itemUserColumn] = $otherUser->id;
        $team = $this->newItem($this->itemAttributes);
        $newName = $this->faker->sentence;
        $team->{$this->itemColumn} = $newName;

        $response = $this->updateItem($team->id, $team->toArray());

        $response->assertStatus(403);
    }

    /** @test */
    public function authorized_user_can_delete_the_team()
    {
        $this->signIn();
        $this->itemAttributes[$this->itemUserColumn] = $this->user->id;
        $team = $this->newItem($this->itemAttributes);

        $response = $this->deleteItem($team->id);

        $model = new $this->base_model;
        $this->assertEquals(0, $model->all()->count());
        $this->assertDatabaseMissing($model->getTable(), [
            $this->itemUserColumn => $this->user->id,
            $this->itemColumn => $team->{$this->itemColumn},
        ]);

        // $response->assertSessionHas(['successMessage']);
        // $response->assertSessionHas('successMessage', __('my_app.messages.data_deleted'));

        $response->assertSessionHas(['flash_notification']);
        $session = session('flash_notification')[0];
        $this->assertEquals($session['message'], __('my_app.messages.data_deleted'));
        $response->assertStatus(302);
    }

    /** @test */
    public function authorized_user_cannot_delete_others_team()
    {
        $this->signIn();
        $otherUser = create(User::class);
        $this->itemAttributes[$this->itemUserColumn] = $otherUser->id;
        $team = $this->newItem($this->itemAttributes);

        $response = $this->deleteItem($team->id);

        $model = new $this->base_model;
        $this->assertEquals(1, $model->all()->count());
        $this->assertDatabaseHas($model->getTable(), [
            $this->itemUserColumn => $otherUser->id,
            $this->itemColumn => $team->{$this->itemColumn},
        ]);
        $response->assertStatus(403);
    }
}
