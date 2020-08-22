<?php

namespace Modules\ExampleBlog\tests\Feature\Http\Controllers\Backend;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Modules\ExampleBlog\Entities\ExampleBlogChannel as Channel;

class ChannelControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->setBaseRoute('example.blog.backend.channels');
        $this->setBaseModel(Channel::class);
    }

    /** @test */
    public function guest_cannot_read_channels()
    {
        $channel = create($this->base_model);

        $response = $this->readAllItems();

        $response->assertStatus(302);
        $response->assertRedirect('login');
        $response->assertDontSee($channel->name);
    }


    /** @test */
    public function authenticated_user_can_read_all_their_channels()
    {
        $this->signIn();
        $channel = $this->newItem(['owner_id' => $this->user->id]);

        $response = $this->readAllItems();

        $response->assertStatus(200);
        $response->assertViewIs('exampleblog::channels.index');
		$response->assertViewHas('channels');
        $response->assertSee($channel->name);
    }

    /** @test */
    public function authenticated_user_cannot_read_others_channels()
    {
        $this->signIn();
        $otherUser = create(User::class);
        $channel = $this->newItem(['owner_id' => $otherUser->id]);

        $response = $this->readAllItems();

        $response->assertStatus(200);
        $response->assertDontSee($channel->name);
    }

    /** @test */
    public function guest_cannot_create_a_new_channel()
    {
        $response = $this->visitCreatePage();

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    /** @test */
    public function authenticated_user_can_create_a_new_channel()
    {
        $this->signIn();

        $response = $this->visitCreatePage();

        $response->assertStatus(200);
        $response->assertViewIs('exampleblog::channels.create');
		$response->assertViewHas('channel');

        $data = raw($this->base_model);
        unset($data['owner_id']);
        $response = $this->createItem($data);

        $model = new $this->base_model;
        $this->assertEquals(1, $model->all()->count());
        $this->assertDatabaseHas($model->getTable(), $data);
        $response->assertSessionHas(['successMessage']);
        $response->assertSessionHas('successMessage', __('exampleblog::channel.messages.data_created'));
        $response->assertStatus(302);
    }

    /** @test */
    public function guest_cannot_view_a_channel()
    {
        $channel = $this->newItem();

        $response = $this->readItem($channel->id);

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    /** @test */
    public function authenticated_user_can_view_a_channel()
    {
        $this->signIn();
        $channel = $this->newItem(['owner_id' => $this->user->id]);

        $response = $this->readItem($channel->id);

        $response->assertStatus(200);
        $response->assertViewIs('exampleblog::channels.show');
		$response->assertViewHas('channel');
    }

    /** @test */
    public function authenticated_user_cannot_view_others_channel()
    {
        $this->signIn();
        $otherUser = create(User::class);
        $channel = $this->newItem(['owner_id' => $otherUser->id]);

        $response = $this->readItem($channel->id);

        $response->assertStatus(403);
    }

    /** @test */
    public function guest_cannot_update_the_channel()
    {
        $channel = create($this->base_model);

        $response = $this->visitEditPage($channel->id);

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    /** @test */
    public function authorized_user_can_update_the_channel()
    {
        $this->signIn();

        $channel = $this->newItem(['owner_id' => $this->user->id]);
        $oldName = $channel->name;
        $newName = $this->faker->sentence;
        $channel->name = $newName;

        $response = $this->visitEditPage($channel->id);

        $response->assertStatus(200);
        $response->assertViewIs('exampleblog::channels.edit');
		$response->assertViewHas('channel');

        $response = $this->updateItem($channel->id, $channel->toArray());

        $model = new $this->base_model;
        $this->assertEquals(1, $model->all()->count());
        $this->assertDatabaseHas($model->getTable(), [
            'owner_id' => $this->user->id,
            'name' => $newName,
        ]);
        $this->assertDatabaseMissing($model->getTable(), [
            'owner_id' => $this->user->id,
            'name' => $oldName,
        ]);
        $response->assertSessionHas(['successMessage']);
        $response->assertSessionHas('successMessage', __('exampleblog::channel.messages.data_updated'));
        $response->assertStatus(302);
    }

    /** @test */
    public function authorized_user_cannot_update_others_channel()
    {
        $this->signIn();
        $otherUser = create(User::class);
        $channel = $this->newItem(['owner_id' => $otherUser->id]);
        $newName = $this->faker->sentence;
        $channel->name = $newName;

        $response = $this->updateItem($channel->id, $channel->toArray());

        $response->assertStatus(403);
    }

    /** @test */
    public function authorized_user_can_delete_the_task()
    {
        $this->signIn();
        $channel = $this->newItem(['owner_id' => $this->user->id]);

        $response = $this->deleteItem($channel->id);

        $model = new $this->base_model;
        $this->assertEquals(0, $model->all()->count());
        $this->assertDatabaseMissing($model->getTable(), [
            'owner_id' => $this->user->id,
            'name' => $channel->name,
        ]);
        $response->assertSessionHas(['successMessage']);
        $response->assertSessionHas('successMessage', __('exampleblog::channel.messages.data_deleted'));
        $response->assertStatus(302);
    }

    /** @test */
    public function authorized_user_cannot_delete_others_task()
    {
        $this->signIn();
        $otherUser = create(User::class);
        $channel = $this->newItem(['owner_id' => $otherUser->id]);

        $response = $this->deleteItem($channel->id);

        $model = new $this->base_model;
        $this->assertEquals(1, $model->all()->count());
        $this->assertDatabaseHas($model->getTable(), [
            'owner_id' => $otherUser->id,
            'name' => $channel->name,
        ]);
        $response->assertStatus(403);
    }
}
