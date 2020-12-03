<?php

namespace Modules\ExampleBlog\tests\Feature\Http\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Modules\ExampleBlog\Models\Tag;

class TagControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public $itemColumn;
    public $itemUserColumn;
    public $itemAttributes;

    public function setUp(): void
    {
        parent::setUp();

        $this->setBaseRoute('example-blog.tags');
        $this->setBaseModel(Tag::class);

        $attributes = [];

        $this->itemUserColumn = 'owner_id';
        $this->itemColumn = 'name';
        $this->itemAttributes = $attributes;
    }

    /** @test */
    public function guest_cannot_read_tags()
    {
        $tag = create($this->base_model, $this->itemAttributes);

        $response = $this->readAllItems();

        $response->assertStatus(302);
        $response->assertRedirect('login');
        $response->assertDontSee($tag->{$this->itemColumn});
    }


    /** @test */
    public function authenticated_user_can_read_all_their_tags()
    {
        $this->signIn();
        $this->itemAttributes[$this->itemUserColumn] = $this->user->id;
        $this->itemAttributes['ownerable_type'] = 'App\User';
        $this->itemAttributes['ownerable_id'] = $this->user->id;
        $tag = $this->newItem($this->itemAttributes);

        $response = $this->readAllItems();

        $response->assertStatus(200);
        $response->assertViewIs('exampleblog::tags.index');
		$response->assertViewHas('tags');
        $response->assertSee($tag->{$this->itemColumn});
    }

    /** @test */
    public function authenticated_user_cannot_read_others_tags()
    {
        $this->signIn();
        $otherUser = create(User::class);
        $this->itemAttributes[$this->itemUserColumn] = $otherUser->id;
        $tag = $this->newItem($this->itemAttributes);

        $response = $this->readAllItems();

        $response->assertStatus(200);
        $response->assertDontSee($tag->{$this->itemColumn});
    }

    /** @test */
    public function guest_cannot_create_a_new_tag()
    {
        $response = $this->visitCreatePage();

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    /** @test */
    public function authenticated_user_can_create_a_new_tag()
    {
        $this->signIn();

        $response = $this->visitCreatePage();

        $response->assertStatus(200);
        $response->assertViewIs('exampleblog::tags.create');
		$response->assertViewHas('tag');

        $data = raw($this->base_model, $this->itemAttributes);
        unset($data[$this->itemUserColumn]);
        unset($data['slug']);
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
    public function guest_cannot_view_a_tag()
    {
        $tag = $this->newItem($this->itemAttributes);

        $response = $this->readItem($tag->id);

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    /** @test */
    public function authenticated_user_can_view_a_tag()
    {
        $this->signIn();
        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $attributes['ownerable_id'] = $this->user->id;
        $attributes['ownerable_type'] = get_class($this->user);
        $tag = $this->newItem($attributes);

        $response = $this->readItem($tag->id);

        $response->assertStatus(200);
        $response->assertViewIs('exampleblog::tags.show');
		$response->assertViewHas('tag');
    }

    /** @test */
    public function authenticated_user_cannot_view_others_tag()
    {
        $this->signIn();
        $otherUser = create(User::class);
        $this->itemAttributes[$this->itemUserColumn] = $otherUser->id;
        $tag = $this->newItem($this->itemAttributes);

        $response = $this->readItem($tag->id);

        $response->assertStatus(403);
    }

    /** @test */
    public function guest_cannot_update_the_tag()
    {
        $tag = create($this->base_model, $this->itemAttributes);

        $response = $this->visitEditPage($tag->id);

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    /** @test */
    public function authorized_user_can_update_the_tag()
    {
        $this->signIn();

        $this->itemAttributes[$this->itemUserColumn] = $this->user->id;
        $tag = $this->newItem($this->itemAttributes);
        $oldName = $tag->{$this->itemColumn};
        $newName = $this->faker->sentence;
        $tag->{$this->itemColumn} = $newName;

        $response = $this->visitEditPage($tag->id);

        $response->assertStatus(200);
        $response->assertViewIs('exampleblog::tags.edit');
		$response->assertViewHas('tag');

        $response = $this->updateItem($tag->id, $tag->toArray());

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
    public function authorized_user_cannot_update_others_tag()
    {
        $this->signIn();
        $otherUser = create(User::class);
        $this->itemAttributes[$this->itemUserColumn] = $otherUser->id;
        $tag = $this->newItem($this->itemAttributes);
        $newName = $this->faker->sentence;
        $tag->{$this->itemColumn} = $newName;

        $response = $this->updateItem($tag->id, $tag->toArray());

        $response->assertStatus(403);
    }

    /** @test */
    public function authorized_user_can_delete_the_tag()
    {
        $this->signIn();
        $this->itemAttributes[$this->itemUserColumn] = $this->user->id;
        $tag = $this->newItem($this->itemAttributes);

        $response = $this->deleteItem($tag->id);

        $model = new $this->base_model;
        $this->assertEquals(0, $model->all()->count());
        $this->assertDatabaseMissing($model->getTable(), [
            $this->itemUserColumn => $this->user->id,
            $this->itemColumn => $tag->{$this->itemColumn},
        ]);

        // $response->assertSessionHas(['successMessage']);
        // $response->assertSessionHas('successMessage', __('my_app.messages.data_deleted'));

        $response->assertSessionHas(['flash_notification']);
        $session = session('flash_notification')[0];
        $this->assertEquals($session['message'], __('my_app.messages.data_deleted'));
        $response->assertStatus(302);
    }

    /** @test */
    public function authorized_user_cannot_delete_others_tag()
    {
        $this->signIn();
        $otherUser = create(User::class);
        $this->itemAttributes[$this->itemUserColumn] = $otherUser->id;
        $tag = $this->newItem($this->itemAttributes);

        $response = $this->deleteItem($tag->id);

        $model = new $this->base_model;
        $this->assertEquals(1, $model->all()->count());
        $this->assertDatabaseHas($model->getTable(), [
            $this->itemUserColumn => $otherUser->id,
            $this->itemColumn => $tag->{$this->itemColumn},
        ]);
        $response->assertStatus(403);
    }
}
