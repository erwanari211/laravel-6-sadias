<?php

namespace Modules\ExampleBlog\Tests\Feature\Http\Requests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Modules\ExampleBlog\Entities\ExampleBlogChannel as Channel;

class ExampleBlogChannelRequestTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->setBaseRoute('example.blog.backend.channels');
        $this->setBaseModel(Channel::class);
    }

    private function getItemFields($overrides = [])
    {
        $item = make($this->base_model);
        $data = $item->toArray();
        $itemFields = array_merge($data, $overrides);
        return $itemFields;
    }

    /**
     * @test
     * @dataProvider storeChannelDataProvider
     */
    public function it_validate_store_channel_request($field, $formFields, $shouldPass = false)
    {
        $this->signIn();
        $this->newItem(['slug' => 'channel-slug']);

        $response = $this->createItem($this->getItemFields($formFields));

        if (!$shouldPass) {
            $response->assertSessionHasErrors([$field]);
        } else {
            $response->assertSessionHasNoErrors([$field]);
        }
    }

    public function storeChannelDataProvider()
    {
        return [
            'request_should_fail_when_no_name_is_provided' => [
                'field' => 'name',
                'data' => ['name' => ''],
            ],
            'request_should_success_when_name_is_provided' => [
                'field' => 'name',
                'data' => ['name' => 'name'],
                'passed' => true,
            ],
            'request_should_fail_when_no_slug_is_provided' => [
                'field' => 'slug',
                'data' => ['slug' => ''],
            ],
            'request_should_success_when_slug_is_provided' => [
                'field' => 'slug',
                'data' => ['slug' => 'new-channel-slug'],
                'passed' => true,
            ],
            'request_should_fail_when_slug_is_not_unique' => [
                'field' => 'slug',
                'data' => ['slug' => 'channel-slug'],
            ],
            'request_should_fail_when_slug_with_str_slug_already_exists' => [
                'field' => 'slug',
                'data' => ['slug' => 'channel slug'],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider updateChannelDataProvider
     */
    public function it_validate_update_channel_request($field, $formFields, $shouldPass = false)
    {
        $this->signIn();
        $this->data['channel'] = $channel = $this->newItem([
            'slug' => 'channel-slug',
            'owner_id' => $this->user->id
        ]);
        $this->data['otherChannel'] = $this->newItem([
            'slug' => 'other-channel-slug'
        ]);

        $response = $this->updateItem($channel->id, $this->getItemFields($formFields));

        if (!$shouldPass) {
            $response->assertSessionHasErrors([$field]);
        } else {
            $response->assertSessionHasNoErrors([$field]);
        }
    }

    public function updateChannelDataProvider()
    {
        return [
            'request_should_fail_when_no_name_is_provided' => [
                'field' => 'name',
                'data' => ['name' => ''],
            ],
            'request_should_success_when_name_is_provided' => [
                'field' => 'name',
                'data' => ['name' => 'name'],
                'passed' => true,
            ],
            'request_should_fail_when_no_slug_is_provided' => [
                'field' => 'slug',
                'data' => ['slug' => ''],
            ],
            'request_should_success_when_slug_is_provided' => [
                'field' => 'slug',
                'data' => ['slug' => 'new-channel-slug'],
                'passed' => true,
            ],
            'request_should_fail_when_slug_is_not_unique' => [
                'field' => 'slug',
                'data' => ['slug' => 'other-channel-slug'],
            ],
            'request_should_fail_when_slug_with_str_slug_already_exists' => [
                'field' => 'slug',
                'data' => ['slug' => 'other channel slug'],
            ],
            'request_should_success_when_slug_same_as_current_slug' => [
                'field' => 'slug',
                'data' => ['slug' => 'channel-slug'],
                'passed' => true,
            ],
            'request_should_success_when_slug_with_str_slug_same_as_current_slug' => [
                'field' => 'slug',
                'data' => ['slug' => 'channel slug'],
                'passed' => true,
            ],
        ];
    }

    /** @test */
    public function authenticated_user_can_create_channel()
    {
        $this->signIn();

        $this->createItem();

        $model = new $this->base_model;
        $this->assertDatabaseHas($model->getTable(), ['owner_id' => $this->user->id]);
    }

    /** @test */
    public function guest_cannot_create_channel()
    {
        $response = $this->createItem();

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    /** @test */
    public function authenticated_user_can_update_channel()
    {
        $this->signIn();
        $channel = $this->newItem(['owner_id' => $this->user->id]);
        $oldName = $channel->name;

        $this->updateItem($channel->id);

        $channel->refresh();
        $model = new $this->base_model;
        $this->assertDatabaseHas($model->getTable(), [
            'owner_id' => $this->user->id,
            'name' => $channel->name,
        ]);
        $this->assertDatabaseMissing($model->getTable(), [
            'owner_id' => $this->user->id,
            'name' => $oldName,
        ]);
    }

    /** @test */
    public function authenticated_user_cannot_update_others_channel()
    {
        $this->signIn();
        $otherChannel = $this->newItem();

        $response = $this->updateItem($otherChannel->id);

        $response->assertStatus(403);
    }
}
