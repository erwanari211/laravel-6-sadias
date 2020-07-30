<?php

namespace Modules\ExampleBlog\Tests\Unit\Services;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\ExampleBlog\Services\ExampleBlogChannelService as ChannelService;
use App\User;
use Modules\ExampleBlog\Entities\ExampleBlogChannel as Channel;

class ExampleBlogChannelServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->setBaseRoute('example.blog.backend.channels');
        $this->setBaseModel(Channel::class);
    }


    /** @test */
    public function it_can_fetch_users_channels()
    {
        $service = new ChannelService;
        $this->signIn();
        $channel = $this->newItem(['owner_id' => $this->user->id]);
        $channel->load('owner');

        $channels = $service->getData();

        $channelsArray = $channels->jsonSerialize();
        $channelArray = $channel->toArray();
        $this->assertContains($channelArray, $channelsArray);
        $this->assertCount(1, $channelsArray);
        $this->assertArrayHasKey('owner', $channelsArray[0]);
        $this->assertEquals($channelsArray[0]['owner_id'], $channelArray['owner_id']);
        $this->assertEquals($channelsArray[0]['owner_id'], $this->user->id);
        $this->assertEquals($channelsArray[0]['name'], $channelArray['name']);
        $this->assertEquals($channelsArray[0]['slug'], $channelArray['slug']);
    }

    /** @test */
    public function it_cannot_fetch_others_channels()
    {
        $service = new ChannelService;
        $this->signIn();
        $channel = $this->newItem(['owner_id' => $this->user->id]);
        $channel->load('owner');
        $otherChannel = $this->newItem();

        $channels = $service->getData();

        $channelsArray = $channels->jsonSerialize();
        $channelArray = $channel->toArray();
        $otherChannelArray = $otherChannel->toArray();

        $this->assertContains($channelArray, $channelsArray);
        $this->assertNotContains($otherChannelArray, $channelsArray);
        $this->assertCount(1, $channelsArray);
        $this->assertNotCount(2, $channelsArray);
    }

    /** @test */
    public function it_can_fetch_a_users_channel()
    {
        $service = new ChannelService;
        $this->signIn();
        $channel = $this->newItem(['owner_id' => $this->user->id]);
        $channel->load('owner');

        $item = $service->getItem($channel->id);

        $channelArray = $channel->toArray();
        $this->assertEquals($item['owner_id'], $channelArray['owner_id']);
        $this->assertEquals($item['owner_id'], $this->user->id);
        $this->assertEquals($item['name'], $channelArray['name']);
        $this->assertEquals($item['slug'], $channelArray['slug']);
    }

    /** @test */
    public function it_can_create_channel()
    {
        $service = new ChannelService;
        $this->signIn();

        $sentence = $this->faker->sentence;
        $data = make($this->base_model, ['owner_id' => null, 'slug' => $sentence]);
        $slug = \Str::slug($sentence);

        $channel = $service->create($data->toArray());

        $this->assertEquals($channel['owner_id'], $this->user->id);
        $this->assertNotEquals($channel['slug'], $sentence);
        $this->assertEquals($channel['slug'], $slug);
        $model = new $this->base_model;
        $this->assertDatabaseHas($model->getTable(), [
            'owner_id' => $this->user->id,
            'slug' => $slug
        ]);
    }

    /** @test */
    public function it_can_update_channel()
    {
        $service = new ChannelService;
        $this->signIn();

        $channel = $this->newItem(['owner_id' => $this->user->id]);
        $sentence = $this->faker->sentence;
        $data = make($this->base_model, ['owner_id' => null, 'slug' => $sentence]);
        $slug = \Str::slug($sentence);
        $oldName = $channel->name;

        $updatedChannel = $service->update($channel, $data->toArray());

        $this->assertNotEquals($updatedChannel['name'], $oldName);
        $this->assertNotEquals($updatedChannel['slug'], $sentence);
        $this->assertEquals($updatedChannel['slug'], $slug);
        $model = new $this->base_model;
        $this->assertDatabaseHas($model->getTable(), [
            'slug' => $slug
        ]);
        $this->assertDatabaseMissing($model->getTable(), [
            'name' => $oldName
        ]);
    }

    /** @test */
    public function it_can_delete_channel()
    {
        $model = new $this->base_model;
        $service = new ChannelService;
        $this->signIn();

        $channel = $this->newItem(['owner_id' => $this->user->id]);
        $this->assertEquals(1, $model->all()->count());
        $oldName = $channel->name;

        $service->delete($channel);

        $this->assertEquals(0, $model->all()->count());
        $this->assertDatabaseMissing($model->getTable(), [
            'name' => $oldName
        ]);
    }
}
