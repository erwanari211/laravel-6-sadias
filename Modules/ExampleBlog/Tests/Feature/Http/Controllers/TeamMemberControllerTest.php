<?php

namespace Modules\ExampleBlog\tests\Feature\Http\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Modules\ExampleBlog\Models\TeamMember;

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

        $attributes = [];

        $this->itemUserColumn = 'user_id';
        $this->itemColumn = 'role_name';
        $this->itemAttributes = $attributes;
    }

    /** @test */
    public function guest_cannot_read_teamMembers()
    {
        $teamMember = create($this->base_model, $this->itemAttributes);

        $response = $this->readAllItems();

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

        $response = $this->readAllItems();

        $response->assertStatus(200);
        $response->assertViewIs('exampleblog::team-members.index');
		$response->assertViewHas('teamMembers');
        $response->assertSee($teamMember->{$this->itemColumn});
    }

    /** @test */
    public function authenticated_user_cannot_read_others_teamMembers()
    {
        $this->signIn();
        $otherUser = create(User::class);
        $this->itemAttributes[$this->itemUserColumn] = $otherUser->id;
        $teamMember = $this->newItem($this->itemAttributes);

        $response = $this->readAllItems();

        $response->assertStatus(200);
        $response->assertDontSee($teamMember->{$this->itemColumn});
    }

    /** @test */
    public function guest_cannot_create_a_new_teamMember()
    {
        $response = $this->visitCreatePage();

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    /** @test */
    public function authenticated_user_can_create_a_new_teamMember()
    {
        $this->signIn();

        $response = $this->visitCreatePage();

        $response->assertStatus(200);
        $response->assertViewIs('exampleblog::team-members.create');
		$response->assertViewHas('teamMember');

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
    public function guest_cannot_view_a_teamMember()
    {
        $teamMember = $this->newItem($this->itemAttributes);

        $response = $this->readItem($teamMember->id);

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    /** @test */
    public function authenticated_user_can_view_a_teamMember()
    {
        $this->signIn();
        $this->itemAttributes[$this->itemUserColumn] = $this->user->id;
        $teamMember = $this->newItem($this->itemAttributes);

        $response = $this->readItem($teamMember->id);

        $response->assertStatus(200);
        $response->assertViewIs('exampleblog::team-members.show');
		$response->assertViewHas('teamMember');
    }

    /** @test */
    public function authenticated_user_cannot_view_others_teamMember()
    {
        $this->signIn();
        $otherUser = create(User::class);
        $this->itemAttributes[$this->itemUserColumn] = $otherUser->id;
        $teamMember = $this->newItem($this->itemAttributes);

        $response = $this->readItem($teamMember->id);

        $response->assertStatus(403);
    }

    /** @test */
    public function guest_cannot_update_the_teamMember()
    {
        $teamMember = create($this->base_model, $this->itemAttributes);

        $response = $this->visitEditPage($teamMember->id);

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    /** @test */
    public function authorized_user_can_update_the_teamMember()
    {
        $this->signIn();

        $this->itemAttributes[$this->itemUserColumn] = $this->user->id;
        $teamMember = $this->newItem($this->itemAttributes);
        $oldName = $teamMember->{$this->itemColumn};
        $newName = $this->faker->sentence;
        $teamMember->{$this->itemColumn} = $newName;

        $response = $this->visitEditPage($teamMember->id);

        $response->assertStatus(200);
        $response->assertViewIs('exampleblog::team-members.edit');
		$response->assertViewHas('teamMember');

        $response = $this->updateItem($teamMember->id, $teamMember->toArray());

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
    public function authorized_user_cannot_update_others_teamMember()
    {
        $this->signIn();
        $otherUser = create(User::class);
        $this->itemAttributes[$this->itemUserColumn] = $otherUser->id;
        $teamMember = $this->newItem($this->itemAttributes);
        $newName = $this->faker->sentence;
        $teamMember->{$this->itemColumn} = $newName;

        $response = $this->updateItem($teamMember->id, $teamMember->toArray());

        $response->assertStatus(403);
    }

    /** @test */
    public function authorized_user_can_delete_the_teamMember()
    {
        $this->signIn();
        $this->itemAttributes[$this->itemUserColumn] = $this->user->id;
        $teamMember = $this->newItem($this->itemAttributes);

        $response = $this->deleteItem($teamMember->id);

        $model = new $this->base_model;
        $this->assertEquals(0, $model->all()->count());
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
        $otherUser = create(User::class);
        $this->itemAttributes[$this->itemUserColumn] = $otherUser->id;
        $teamMember = $this->newItem($this->itemAttributes);

        $response = $this->deleteItem($teamMember->id);

        $model = new $this->base_model;
        $this->assertEquals(1, $model->all()->count());
        $this->assertDatabaseHas($model->getTable(), [
            $this->itemUserColumn => $otherUser->id,
            $this->itemColumn => $teamMember->{$this->itemColumn},
        ]);
        $response->assertStatus(403);
    }
}
