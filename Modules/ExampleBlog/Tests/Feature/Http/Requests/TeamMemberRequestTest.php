<?php

namespace Modules\ExampleBlog\tests\Feature\Http\Requests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Modules\ExampleBlog\Models\TeamMember;

class TeamMemberRequestTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public $itemColumn;
    public $itemUserColumn;
    public $itemAttributes;

    public function setUp(): void
    {
        parent::setUp();

        $this->setBaseRoute('example-blog.team-members');
        $this->setBaseModel(TeamMember::class);

        $attributes = [];

        $this->itemUserColumn = 'user_id';
        $this->itemColumn = 'role_name';
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
    public function it_validate_store_teamMember_request($field, $formFields, $shouldPass = false)
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

    }

    public function storeItemDataProvider()
    {
        return [

            'request_should_fail_when_no_user_id_is_provided' => [
                'field' => 'user_id',
                'data' => ['user_id' => ''],
                'passed' => false,
            ],
            'request_should_success_when_user_id_is_provided' => [
                'field' => 'user_id',
                'data' => ['user_id' => '99'],
                'passed' => true,
            ],

            'request_should_fail_when_user_id_value_is_not_integer' => [
                'field' => 'user_id',
                'data' => ['user_id' => 'not-integer'],
                'passed' => false,
            ],
            'request_should_success_when_user_id_value_is_not_integer' => [
                'field' => 'user_id',
                'data' => ['user_id' => '99'],
                'passed' => true,
            ],

            'request_should_fail_when_no_team_id_is_provided' => [
                'field' => 'team_id',
                'data' => ['team_id' => ''],
                'passed' => false,
            ],
            'request_should_success_when_team_id_is_provided' => [
                'field' => 'team_id',
                'data' => ['team_id' => '99'],
                'passed' => true,
            ],

            'request_should_fail_when_team_id_value_is_not_integer' => [
                'field' => 'team_id',
                'data' => ['team_id' => 'not-integer'],
                'passed' => false,
            ],
            'request_should_success_when_team_id_value_is_not_integer' => [
                'field' => 'team_id',
                'data' => ['team_id' => '99'],
                'passed' => true,
            ],

            'request_should_fail_when_no_role_name_is_provided' => [
                'field' => 'role_name',
                'data' => ['role_name' => ''],
                'passed' => false,
            ],
            'request_should_success_when_role_name_is_provided' => [
                'field' => 'role_name',
                'data' => ['role_name' => 'role_name'],
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
    public function it_validate_update_teamMember_request($field, $formFields, $shouldPass = false)
    {
        $this->signIn();
        $this->beforeValidateUpdateRequest();

        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $teamMember = $this->newItem($attributes);

        $response = $this->updateItem($teamMember->id, $this->getItemFields($formFields));

        if (!$shouldPass) {
            $response->assertSessionHasErrors([$field]);
        } else {
            $response->assertSessionHasNoErrors([$field]);
        }
    }

    public function beforeValidateUpdateRequest()
    {

    }

    public function updateItemDataProvider()
    {
        return [

            'request_should_fail_when_no_user_id_is_provided' => [
                'field' => 'user_id',
                'data' => ['user_id' => ''],
                'passed' => false,
            ],
            'request_should_success_when_user_id_is_provided' => [
                'field' => 'user_id',
                'data' => ['user_id' => '99'],
                'passed' => true,
            ],

            'request_should_fail_when_user_id_value_is_not_integer' => [
                'field' => 'user_id',
                'data' => ['user_id' => 'not-integer'],
                'passed' => false,
            ],
            'request_should_success_when_user_id_value_is_not_integer' => [
                'field' => 'user_id',
                'data' => ['user_id' => '99'],
                'passed' => true,
            ],

            'request_should_fail_when_no_team_id_is_provided' => [
                'field' => 'team_id',
                'data' => ['team_id' => ''],
                'passed' => false,
            ],
            'request_should_success_when_team_id_is_provided' => [
                'field' => 'team_id',
                'data' => ['team_id' => '99'],
                'passed' => true,
            ],

            'request_should_fail_when_team_id_value_is_not_integer' => [
                'field' => 'team_id',
                'data' => ['team_id' => 'not-integer'],
                'passed' => false,
            ],
            'request_should_success_when_team_id_value_is_not_integer' => [
                'field' => 'team_id',
                'data' => ['team_id' => '99'],
                'passed' => true,
            ],

            'request_should_fail_when_no_role_name_is_provided' => [
                'field' => 'role_name',
                'data' => ['role_name' => ''],
                'passed' => false,
            ],
            'request_should_success_when_role_name_is_provided' => [
                'field' => 'role_name',
                'data' => ['role_name' => 'role_name'],
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
    public function authenticated_user_can_create_teamMember()
    {
        $this->signIn();

        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $this->createItem($attributes);

        $model = new $this->base_model;
        $this->assertDatabaseHas($model->getTable(), [$this->itemUserColumn => $this->user->id]);
    }

    /** @test */
    public function guest_cannot_create_teamMember()
    {
        $attributes = $this->itemAttributes;
        $response = $this->createItem($attributes);

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    /** @test */
    public function authenticated_user_can_update_teamMember()
    {
        $this->signIn();

        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $teamMember = $this->newItem($attributes);
        $oldName = $teamMember->{$this->itemColumn};

        $this->updateItem($teamMember->id, $attributes);

        $teamMember->refresh();
        $model = new $this->base_model;
        $this->assertDatabaseHas($model->getTable(), [
            $this->itemUserColumn => $this->user->id,
            $this->itemColumn => $teamMember->{$this->itemColumn},
        ]);
        $this->assertDatabaseMissing($model->getTable(), [
            $this->itemUserColumn => $this->user->id,
            $this->itemColumn => $oldName,
        ]);
    }

    /** @test */
    public function authenticated_user_cannot_update_others_teamMember()
    {
        $this->signIn();
        $attributes = $this->itemAttributes;
        $other = $this->newItem($this->itemAttributes);

        $response = $this->updateItem($other->id);

        $response->assertStatus(403);
    }
}
