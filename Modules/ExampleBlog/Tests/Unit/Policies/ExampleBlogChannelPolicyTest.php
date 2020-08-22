<?php

namespace Modules\ExampleBlog\tests\Unit\Policies;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\ExampleBlog\Entities\ExampleBlogChannel as Channel;

class ExampleBlogChannelPolicyTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->setBaseRoute('example.blog.backend.channels');
        $this->setBaseModel(Channel::class);
    }

    /** @test */
    public function owner_can_create_channel()
    {
        $this->signIn();
        $model = new $this->base_model;
        $this->assertTrue($this->user->can('create', $model));
    }

    /** @test */
    public function owner_can_read_channel()
    {
        $this->signIn();
        $channel = $this->newItem(['owner_id' => $this->user->id]);
        $this->assertTrue($this->user->can('view', $channel));
    }

    /** @test */
    public function owner_can_update_channel()
    {
        $this->signIn();
        $channel = $this->newItem(['owner_id' => $this->user->id]);
        $this->assertTrue($this->user->can('update', $channel));
    }

    /** @test */
    public function owner_can_delete_channel()
    {
        $this->signIn();
        $channel = $this->newItem(['owner_id' => $this->user->id]);
        $this->assertTrue($this->user->can('delete', $channel));
    }

    /** @test */
    public function user_cannot_access_others_channel()
    {
        $this->signIn();
        $channel = $this->newItem();
        $this->assertFalse($this->user->can('view', $channel));
        $this->assertFalse($this->user->can('update', $channel));
        $this->assertFalse($this->user->can('delete', $channel));
    }
}
