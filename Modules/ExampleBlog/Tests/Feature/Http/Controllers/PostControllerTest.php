<?php

namespace Modules\ExampleBlog\tests\Feature\Http\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Modules\ExampleBlog\Models\Post;

class PostControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public $itemColumn;
    public $itemUserColumn;
    public $itemAttributes;

    public function setUp(): void
    {
        parent::setUp();

        $this->setBaseRoute('example-blog.posts');
        $this->setBaseModel(Post::class);

        $user = create(User::class);
        $attributes = [
            'postable_id' => $user->id,
            'postable_type' => get_class($user),
            'status' => 'published'
        ];

        $this->itemUserColumn = 'author_id';
        $this->itemColumn = 'title';
        $this->itemAttributes = $attributes;
    }

    /** @test */
    public function guest_cannot_read_posts()
    {
        $post = create($this->base_model, $this->itemAttributes);

        $response = $this->readAllItems();

        $response->assertStatus(302);
        $response->assertRedirect('login');
        $response->assertDontSee($post->{$this->itemColumn});
    }


    /** @test */
    public function authenticated_user_can_read_all_their_posts()
    {
        $this->signIn();
        $this->itemAttributes[$this->itemUserColumn] = $this->user->id;
        $post = $this->newItem($this->itemAttributes);

        $response = $this->readAllItems();

        $response->assertStatus(200);
        $response->assertViewIs('exampleblog::posts.index');
		$response->assertViewHas('posts');
        $response->assertSee($post->{$this->itemColumn});
    }

    /** @test */
    public function authenticated_user_cannot_read_others_posts()
    {
        $this->signIn();
        $otherUser = create(User::class);
        $this->itemAttributes[$this->itemUserColumn] = $otherUser->id;
        $post = $this->newItem($this->itemAttributes);

        $response = $this->readAllItems();

        $response->assertStatus(200);
        $response->assertDontSee($post->{$this->itemColumn});
    }

    /** @test */
    public function guest_cannot_create_a_new_post()
    {
        $response = $this->visitCreatePage();

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    /** @test */
    public function authenticated_user_can_create_a_new_post()
    {
        $this->signIn();

        $response = $this->visitCreatePage();

        $response->assertStatus(200);
        $response->assertViewIs('exampleblog::posts.create');
		$response->assertViewHas('post');
		$response->assertViewHas('tags');

        $this->itemAttributes['tags'] = [1,2,3];
        $data = raw($this->base_model, $this->itemAttributes);
        unset($data[$this->itemUserColumn]);
        $response = $this->createItem($data);

        $attributes = collect($data)->only(['title', 'content']);
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

        $response = $this->readItem($post->id);

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    /** @test */
    public function authenticated_user_can_view_a_post()
    {
        $this->signIn();
        $this->itemAttributes[$this->itemUserColumn] = $this->user->id;
        $post = $this->newItem($this->itemAttributes);

        $response = $this->readItem($post->id);

        $response->assertStatus(200);
        $response->assertViewIs('exampleblog::posts.show');
		$response->assertViewHas('post');
		$response->assertViewHas('tags');
    }

    /** @test */
    public function authenticated_user_cannot_view_others_post()
    {
        $this->signIn();
        $otherUser = create(User::class);
        $this->itemAttributes[$this->itemUserColumn] = $otherUser->id;
        $post = $this->newItem($this->itemAttributes);

        $response = $this->readItem($post->id);

        $response->assertStatus(403);
    }

    /** @test */
    public function guest_cannot_update_the_post()
    {
        $post = create($this->base_model, $this->itemAttributes);

        $response = $this->visitEditPage($post->id);

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    /** @test */
    public function authorized_user_can_update_the_post()
    {
        $this->signIn();

        $this->itemAttributes[$this->itemUserColumn] = $this->user->id;
        $post = $this->newItem($this->itemAttributes);
        $oldName = $post->{$this->itemColumn};
        $newName = $this->faker->sentence;
        $post->{$this->itemColumn} = $newName;

        $response = $this->visitEditPage($post->id);

        $response->assertStatus(200);
        $response->assertViewIs('exampleblog::posts.edit');
		$response->assertViewHas('post');
		$response->assertViewHas('tags');

        $post->tags = [1,2,3];
        $response = $this->updateItem($post->id, $post->toArray());

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
    public function authorized_user_cannot_update_others_post()
    {
        $this->signIn();
        $otherUser = create(User::class);
        $this->itemAttributes[$this->itemUserColumn] = $otherUser->id;
        $post = $this->newItem($this->itemAttributes);
        $newName = $this->faker->sentence;
        $post->{$this->itemColumn} = $newName;

        $response = $this->updateItem($post->id, $post->toArray());

        $response->assertStatus(403);
    }

    /** @test */
    public function authorized_user_can_delete_the_post()
    {
        $this->signIn();
        $this->itemAttributes[$this->itemUserColumn] = $this->user->id;
        $post = $this->newItem($this->itemAttributes);

        $response = $this->deleteItem($post->id);

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
    public function authorized_user_cannot_delete_others_post()
    {
        $this->signIn();
        $otherUser = create(User::class);
        $this->itemAttributes[$this->itemUserColumn] = $otherUser->id;
        $post = $this->newItem($this->itemAttributes);

        $response = $this->deleteItem($post->id);

        $model = new $this->base_model;
        $this->assertEquals(1, $model->all()->count());
        $this->assertDatabaseHas($model->getTable(), [
            $this->itemUserColumn => $otherUser->id,
            $this->itemColumn => $post->{$this->itemColumn},
        ]);
        $response->assertStatus(403);
    }
}
