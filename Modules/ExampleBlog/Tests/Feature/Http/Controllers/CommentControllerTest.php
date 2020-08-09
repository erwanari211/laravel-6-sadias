<?php

namespace Modules\ExampleBlog\tests\Feature\Http\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Modules\ExampleBlog\Models\Comment;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public $itemColumn;
    public $itemUserColumn;
    public $itemAttributes;

    public function setUp(): void
    {
        parent::setUp();

        $this->setBaseRoute('example-blog.comments');
        $this->setBaseModel(Comment::class);

        $attributes = [
            'status' => 'published',
        ];

        $this->itemUserColumn = 'author_id';
        $this->itemColumn = 'content';
        $this->itemAttributes = $attributes;
    }

    /** @test */
    public function guest_cannot_read_comments()
    {
        $comment = create($this->base_model, $this->itemAttributes);

        $response = $this->readAllItems();

        $response->assertStatus(302);
        $response->assertRedirect('login');
        $response->assertDontSee($comment->{$this->itemColumn});
    }


    /** @test */
    public function authenticated_user_can_read_all_their_comments()
    {
        $this->signIn();
        $this->itemAttributes[$this->itemUserColumn] = $this->user->id;
        $comment = $this->newItem($this->itemAttributes);

        $response = $this->readAllItems();

        $response->assertStatus(200);
        $response->assertViewIs('exampleblog::comments.index');
		$response->assertViewHas('comments');
        $response->assertSee($comment->{$this->itemColumn});
    }

    /** @test */
    public function authenticated_user_cannot_read_others_comments()
    {
        $this->signIn();
        $otherUser = create(User::class);
        $this->itemAttributes[$this->itemUserColumn] = $otherUser->id;
        $comment = $this->newItem($this->itemAttributes);

        $response = $this->readAllItems();

        $response->assertStatus(200);
        $response->assertDontSee($comment->{$this->itemColumn});
    }

    /** @test */
    public function guest_cannot_create_a_new_comment()
    {
        $response = $this->visitCreatePage();

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    /** @test */
    public function authenticated_user_can_create_a_new_comment()
    {
        $this->signIn();

        $response = $this->visitCreatePage();

        $response->assertStatus(200);
        $response->assertViewIs('exampleblog::comments.create');
		$response->assertViewHas('comment');

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
    public function guest_cannot_view_a_comment()
    {
        $comment = $this->newItem($this->itemAttributes);

        $response = $this->readItem($comment->id);

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    /** @test */
    public function authenticated_user_can_view_a_comment()
    {
        $this->signIn();
        $this->itemAttributes[$this->itemUserColumn] = $this->user->id;
        $comment = $this->newItem($this->itemAttributes);

        $response = $this->readItem($comment->id);

        $response->assertStatus(200);
        $response->assertViewIs('exampleblog::comments.show');
		$response->assertViewHas('comment');
    }

    /** @test */
    public function authenticated_user_cannot_view_others_comment()
    {
        $this->signIn();
        $otherUser = create(User::class);
        $this->itemAttributes[$this->itemUserColumn] = $otherUser->id;
        $comment = $this->newItem($this->itemAttributes);

        $response = $this->readItem($comment->id);

        $response->assertStatus(403);
    }

    /** @test */
    public function guest_cannot_update_the_comment()
    {
        $comment = create($this->base_model, $this->itemAttributes);

        $response = $this->visitEditPage($comment->id);

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    /** @test */
    public function authorized_user_can_update_the_comment()
    {
        $this->signIn();

        $this->itemAttributes[$this->itemUserColumn] = $this->user->id;
        $comment = $this->newItem($this->itemAttributes);
        $oldName = $comment->{$this->itemColumn};
        $newName = $this->faker->sentence;
        $comment->{$this->itemColumn} = $newName;

        $response = $this->visitEditPage($comment->id);

        $response->assertStatus(200);
        $response->assertViewIs('exampleblog::comments.edit');
		$response->assertViewHas('comment');

        $response = $this->updateItem($comment->id, $comment->toArray());

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
    public function authorized_user_cannot_update_others_comment()
    {
        $this->signIn();
        $otherUser = create(User::class);
        $this->itemAttributes[$this->itemUserColumn] = $otherUser->id;
        $comment = $this->newItem($this->itemAttributes);
        $newName = $this->faker->sentence;
        $comment->{$this->itemColumn} = $newName;

        $response = $this->updateItem($comment->id, $comment->toArray());

        $response->assertStatus(403);
    }

    /** @test */
    public function authorized_user_can_delete_the_comment()
    {
        $this->signIn();
        $this->itemAttributes[$this->itemUserColumn] = $this->user->id;
        $comment = $this->newItem($this->itemAttributes);

        $response = $this->deleteItem($comment->id);

        $model = new $this->base_model;
        $this->assertEquals(0, $model->all()->count());
        $this->assertDatabaseMissing($model->getTable(), [
            $this->itemUserColumn => $this->user->id,
            $this->itemColumn => $comment->{$this->itemColumn},
        ]);

        // $response->assertSessionHas(['successMessage']);
        // $response->assertSessionHas('successMessage', __('my_app.messages.data_deleted'));

        $response->assertSessionHas(['flash_notification']);
        $session = session('flash_notification')[0];
        $this->assertEquals($session['message'], __('my_app.messages.data_deleted'));
        $response->assertStatus(302);
    }

    /** @test */
    public function authorized_user_cannot_delete_others_comment()
    {
        $this->signIn();
        $otherUser = create(User::class);
        $this->itemAttributes[$this->itemUserColumn] = $otherUser->id;
        $comment = $this->newItem($this->itemAttributes);

        $response = $this->deleteItem($comment->id);

        $model = new $this->base_model;
        $this->assertEquals(1, $model->all()->count());
        $this->assertDatabaseHas($model->getTable(), [
            $this->itemUserColumn => $otherUser->id,
            $this->itemColumn => $comment->{$this->itemColumn},
        ]);
        $response->assertStatus(403);
    }
}
