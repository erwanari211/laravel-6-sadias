<?php

namespace Modules\ExampleBlog\tests\Unit\Policies;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Modules\ExampleBlog\Models\Tag;

class TagPolicyTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public $itemColumn;
    public $itemUserColumn;
    public $itemAttributes;

    public function setUp(): void
    {
        parent::setUp();

        $this->setBaseRoute('example.blog.backend.channels');
        $this->setBaseModel(Tag::class);

        $attributes = [];

        $this->itemUserColumn = 'owner_id';
        $this->itemColumn = 'name';
        $this->itemAttributes = $attributes;
    }

    /** @test */
    public function owner_can_create_tag()
    {
        $this->signIn();
        $tag = new $this->base_model;
        $this->assertTrue($this->user->can('create', $tag));
    }

    /** @test */
    public function owner_can_read_tag()
    {
        $this->signIn();
        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $tag = $this->newItem($attributes);
        $this->assertTrue($this->user->can('view', $tag));
    }

    /** @test */
    public function owner_can_update_tag()
    {
        $this->signIn();
        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $tag = $this->newItem($attributes);
        $this->assertTrue($this->user->can('update', $tag));
    }

    /** @test */
    public function owner_can_delete_tag()
    {
        $this->signIn();
        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $tag = $this->newItem($attributes);
        $this->assertTrue($this->user->can('delete', $tag));
    }

    /** @test */
    public function user_cannot_access_others_tag()
    {
        $this->signIn();
        $attributes = $this->itemAttributes;
        $tag = $this->newItem($attributes);
        $this->assertFalse($this->user->can('view', $tag));
        $this->assertFalse($this->user->can('update', $tag));
        $this->assertFalse($this->user->can('delete', $tag));
    }
}
