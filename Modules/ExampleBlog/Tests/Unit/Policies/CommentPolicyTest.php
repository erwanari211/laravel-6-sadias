<?php

namespace Modules\ExampleBlog\tests\Unit\Policies;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Modules\ExampleBlog\Models\Comment;

class CommentPolicyTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public $itemColumn;
    public $itemUserColumn;
    public $itemAttributes;

    public function setUp(): void
    {
        parent::setUp();

        $this->setBaseRoute('example.blog.backend.channels');
        $this->setBaseModel(Comment::class);

        $attributes = [];

        $this->itemUserColumn = 'author_id';
        $this->itemColumn = 'name';
        $this->itemAttributes = $attributes;
    }

    /** @test */
    public function owner_can_create_comment()
    {
        $this->signIn();
        $comment = new $this->base_model;
        $this->assertTrue($this->user->can('create', $comment));
    }

    /** @test */
    public function owner_can_read_comment()
    {
        $this->signIn();
        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $comment = $this->newItem($attributes);
        $this->assertTrue($this->user->can('view', $comment));
    }

    /** @test */
    public function owner_can_update_comment()
    {
        $this->signIn();
        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $comment = $this->newItem($attributes);
        $this->assertTrue($this->user->can('update', $comment));
    }

    /** @test */
    public function owner_can_delete_comment()
    {
        $this->signIn();
        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $comment = $this->newItem($attributes);
        $this->assertTrue($this->user->can('delete', $comment));
    }

    /** @test */
    public function user_cannot_access_others_comment()
    {
        $this->signIn();
        $attributes = $this->itemAttributes;
        $comment = $this->newItem($attributes);
        $this->assertFalse($this->user->can('view', $comment));
        $this->assertFalse($this->user->can('update', $comment));
        $this->assertFalse($this->user->can('delete', $comment));
    }
}
