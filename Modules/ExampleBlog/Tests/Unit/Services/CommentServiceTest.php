<?php

namespace Modules\ExampleBlog\tests\Unit\Services;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Modules\ExampleBlog\Models\Comment;
use Modules\ExampleBlog\Services\CommentService;

class CommentServiceTest extends TestCase
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

        $attributes = [];

        $this->itemUserColumn = 'author_id';
        $this->itemColumn = 'content';
        $this->itemAttributes = $attributes;
    }


    /** @test */
    public function it_can_fetch_users_comments()
    {
        $service = new CommentService;
        $this->signIn();
        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $comment = $this->newItem($attributes);

        $comments = $service->getData();

        $commentsArray = $comments->jsonSerialize();
        // $comment->load('owner');
        $commentArray = $comment->toArray();
        // $this->assertContains($commentArray, $commentsArray);
        // $this->assertArrayHasKey('owner', $commentsArray[0]);

        $this->assertCount(1, $commentsArray);
        $this->assertEquals($commentsArray[0][$this->itemUserColumn], $commentArray[$this->itemUserColumn]);
        $this->assertEquals($commentsArray[0][$this->itemUserColumn], $this->user->id);
        $this->assertEquals($commentsArray[0][$this->itemColumn], $commentArray[$this->itemColumn]);
    }

    /** @test */
    public function it_cannot_fetch_others_comments()
    {
        $service = new CommentService;
        $this->signIn();
        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $comment = $this->newItem($attributes);
        // $comment->load('owner');

        $attributes = $this->itemAttributes;
        $othercomment = $this->newItem($attributes);

        $comments = $service->getData();

        $commentsArray = $comments->jsonSerialize();
        $commentArray = $comment->toArray();
        $othercommentArray = $othercomment->toArray();

        // $this->assertContains($commentArray, $commentsArray);
        $this->assertNotContains($othercommentArray, $commentsArray);

        $this->assertCount(1, $commentsArray);
        $this->assertNotCount(2, $commentsArray);
    }

    /** @test */
    public function it_can_fetch_a_users_comment()
    {
        $service = new CommentService;
        $this->signIn();
        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $comment = $this->newItem($attributes);
        // $comment->load('owner');

        $item = $service->getItem($comment->id);

        $commentArray = $comment->toArray();
        $this->assertEquals($item[$this->itemUserColumn], $commentArray[$this->itemUserColumn]);
        $this->assertEquals($item[$this->itemUserColumn], $this->user->id);
        $this->assertEquals($item[$this->itemColumn], $commentArray[$this->itemColumn]);
    }

    /** @test */
    public function it_can_create_comment()
    {
        $service = new CommentService;
        $this->signIn();

        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $newName = $this->faker->sentence;
        $attributes[$this->itemColumn] = $newName;

        $data = make($this->base_model, $attributes);

        $comment = $service->create($data->toArray());

        $this->assertEquals($comment[$this->itemUserColumn], $this->user->id);
        $this->assertEquals($comment[$this->itemColumn], $newName);
        $model = new $this->base_model;
        $this->assertDatabaseHas($model->getTable(), [
            $this->itemUserColumn => $this->user->id,
            $this->itemColumn => $newName
        ]);
    }

    /** @test */
    public function it_can_update_comment()
    {
        $service = new CommentService;
        $this->signIn();

        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $comment = $this->newItem($attributes);

        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $newName = $this->faker->sentence;
        $attributes[$this->itemColumn] = $newName;

        $data = make($this->base_model, $attributes);
        $oldName = $comment->name;

        $comment = $service->update($comment, $data->toArray());

        $this->assertNotEquals($comment[$this->itemColumn], $oldName);
        $this->assertEquals($comment[$this->itemColumn], $newName);
        $model = new $this->base_model;
        $this->assertDatabaseHas($model->getTable(), [
            $this->itemColumn => $newName
        ]);
        $this->assertDatabaseMissing($model->getTable(), [
            $this->itemColumn => $oldName
        ]);
    }

    /** @test */
    public function it_can_delete_comment()
    {
        $model = new $this->base_model;
        $service = new CommentService;
        $this->signIn();

        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $comment = $this->newItem($attributes);
        $this->assertEquals(1, $model->all()->count());
        $oldName = $comment->{$this->itemColumn};

        $service->delete($comment);

        $this->assertEquals(0, $model->all()->count());
        $this->assertDatabaseMissing($model->getTable(), [
            $this->itemColumn => $oldName
        ]);
    }
}
