<?php

namespace Modules\ExampleBlog\tests\Unit\Policies;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Modules\ExampleBlog\Models\Post;

class PostPolicyTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public $itemColumn;
    public $itemUserColumn;
    public $itemAttributes;

    public function setUp(): void
    {
        parent::setUp();

        $this->setBaseRoute('example.blog.backend.channels');
        $this->setBaseModel(Post::class);

        $user = create(User::class);
        $attributes = [
            'postable_id' => $user->id,
            'postable_type' => get_class($user),
        ];

        $this->itemUserColumn = 'author_id';
        $this->itemColumn = 'name';
        $this->itemAttributes = $attributes;
    }

    /** @test */
    public function owner_can_create_post()
    {
        $this->signIn();
        $post = new $this->base_model;
        $this->assertTrue($this->user->can('create', $post));
    }

    /** @test */
    public function owner_can_read_post()
    {
        $this->signIn();
        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $post = $this->newItem($attributes);
        $this->assertTrue($this->user->can('view', $post));
    }

    /** @test */
    public function owner_can_update_post()
    {
        $this->signIn();
        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $post = $this->newItem($attributes);
        $this->assertTrue($this->user->can('update', $post));
    }

    /** @test */
    public function owner_can_delete_post()
    {
        $this->signIn();
        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $post = $this->newItem($attributes);
        $this->assertTrue($this->user->can('delete', $post));
    }

    /** @test */
    public function user_cannot_access_others_post()
    {
        $this->signIn();
        $attributes = $this->itemAttributes;
        $post = $this->newItem($attributes);
        $this->assertFalse($this->user->can('view', $post));
        $this->assertFalse($this->user->can('update', $post));
        $this->assertFalse($this->user->can('delete', $post));
    }
}
