<?php

namespace Modules\ExampleBlog\tests\Feature\Http\Requests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Modules\ExampleBlog\Models\Team;

class TeamRequestTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public $itemColumn;
    public $itemUserColumn;
    public $itemAttributes;

    public function setUp(): void
    {
        parent::setUp();

        $this->setBaseRoute('example-blog.teams');
        $this->setBaseModel(Team::class);

        $attributes = [];

        $this->itemUserColumn = 'owner_id';
        $this->itemColumn = 'name';
        $this->itemAttributes = $attributes;
    }

    private function getItemFields($overrides = [])
    {
        $item = make($this->base_model, $this->itemAttributes);
        $data = $item->toArray();
        $itemFields = array_merge($data, $overrides);
        return $itemFields;
    }

    /**
     * @test
     * @dataProvider storeItemDataProvider
     */
    public function it_validate_store_team_request($field, $formFields, $shouldPass = false)
    {
        $this->signIn();
        $this->beforeValidateStoreRequest();

        $response = $this->createItem($this->getItemFields($formFields));

        if (!$shouldPass) {
            $response->assertSessionHasErrors([$field]);
        } else {
            $response->assertSessionHasNoErrors([$field]);
        }
    }

    public function beforeValidateStoreRequest()
    {
        $attributes = $this->itemAttributes;
        $attributes['slug'] = 'old-slug';
        $this->newItem($attributes);


    }

    public function storeItemDataProvider()
    {
        return [

            'request_should_fail_when_no_name_is_provided' => [
                'field' => 'name',
                'data' => ['name' => ''],
                'passed' => false,
            ],
            'request_should_success_when_name_is_provided' => [
                'field' => 'name',
                'data' => ['name' => 'name'],
                'passed' => true,
            ],

            'request_should_fail_when_no_slug_is_provided' => [
                'field' => 'slug',
                'data' => ['slug' => ''],
                'passed' => false,
            ],
            'request_should_success_when_slug_is_provided' => [
                'field' => 'slug',
                'data' => ['slug' => 'slug'],
                'passed' => true,
            ],

            'request_should_fail_when_no_slug_is_not_unique' => [
                'field' => 'slug',
                'data' => ['slug' => 'old-slug'],
                'passed' => false,
            ],

            'request_should_success_when_description_is_filled_or_not' => [
                'field' => 'description',
                'data' => ['description' => ''],
                'passed' => true,
            ],

            'request_should_fail_when_no_is_active_is_provided' => [
                'field' => 'is_active',
                'data' => ['is_active' => ''],
                'passed' => false,
            ],
            'request_should_success_when_is_active_is_provided' => [
                'field' => 'is_active',
                'data' => ['is_active' => '1'],
                'passed' => true,
            ],


        ];
    }

    /**
     * @test
     * @dataProvider updateItemDataProvider
     */
    public function it_validate_update_team_request($field, $formFields, $shouldPass = false)
    {
        $this->signIn();
        $this->beforeValidateUpdateRequest();

        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $team = $this->newItem($attributes);

        $response = $this->updateItem($team->id, $this->getItemFields($formFields));

        if (!$shouldPass) {
            $response->assertSessionHasErrors([$field]);
        } else {
            $response->assertSessionHasNoErrors([$field]);
        }
    }

    public function beforeValidateUpdateRequest()
    {
        $attributes = $this->itemAttributes;
        $attributes['slug'] = 'old-slug';
        $this->newItem($attributes);


    }

    public function updateItemDataProvider()
    {
        return [

            'request_should_fail_when_no_name_is_provided' => [
                'field' => 'name',
                'data' => ['name' => ''],
                'passed' => false,
            ],
            'request_should_success_when_name_is_provided' => [
                'field' => 'name',
                'data' => ['name' => 'name'],
                'passed' => true,
            ],

            'request_should_fail_when_no_slug_is_provided' => [
                'field' => 'slug',
                'data' => ['slug' => ''],
                'passed' => false,
            ],
            'request_should_success_when_slug_is_provided' => [
                'field' => 'slug',
                'data' => ['slug' => 'slug'],
                'passed' => true,
            ],

            'request_should_fail_when_no_slug_is_not_unique' => [
                'field' => 'slug',
                'data' => ['slug' => 'old-slug'],
                'passed' => false,
            ],

            'request_should_success_when_description_is_filled_or_not' => [
                'field' => 'description',
                'data' => ['description' => ''],
                'passed' => true,
            ],

            'request_should_fail_when_no_is_active_is_provided' => [
                'field' => 'is_active',
                'data' => ['is_active' => ''],
                'passed' => false,
            ],
            'request_should_success_when_is_active_is_provided' => [
                'field' => 'is_active',
                'data' => ['is_active' => '1'],
                'passed' => true,
            ],


        ];
    }

    /** @test */
    public function authenticated_user_can_create_team()
    {
        $this->signIn();

        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $this->createItem($attributes);

        $model = new $this->base_model;
        $this->assertDatabaseHas($model->getTable(), [$this->itemUserColumn => $this->user->id]);
    }

    /** @test */
    public function guest_cannot_create_team()
    {
        $attributes = $this->itemAttributes;
        $response = $this->createItem($attributes);

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    /** @test */
    public function authenticated_user_can_update_team()
    {
        $this->signIn();

        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $team = $this->newItem($attributes);
        $oldName = $team->{$this->itemColumn};

        $this->updateItem($team->id, $attributes);

        $team->refresh();
        $model = new $this->base_model;
        $this->assertDatabaseHas($model->getTable(), [
            $this->itemUserColumn => $this->user->id,
            $this->itemColumn => $team->{$this->itemColumn},
        ]);
        $this->assertDatabaseMissing($model->getTable(), [
            $this->itemUserColumn => $this->user->id,
            $this->itemColumn => $oldName,
        ]);
    }

    /** @test */
    public function authenticated_user_cannot_update_others_team()
    {
        $this->signIn();
        $attributes = $this->itemAttributes;
        $other = $this->newItem($this->itemAttributes);

        $response = $this->updateItem($other->id);

        $response->assertStatus(403);
    }
}
