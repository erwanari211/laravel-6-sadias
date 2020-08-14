<?php

namespace Modules\ExampleBlog\tests\Unit\Entities;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\ExampleBlog\Entities\ExampleBlogChannel as Channel;
use App\User;

class ExampleBlogChannelTest extends TestCase
{
    /** @test */
    public function a_channel_has_one_owner()
    {
        $user = create(User::class);
        $channel = create(Channel::class, ['owner_id' => $user->id]);
        $this->assertInstanceOf(User::class, $channel->owner);
    }
}
