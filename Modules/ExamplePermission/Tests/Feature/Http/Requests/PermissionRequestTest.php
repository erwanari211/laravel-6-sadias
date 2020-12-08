<?php

namespace Modules\ExamplePermission\Tests\Feature\Http\Requests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Modules\ExamplePermission\Models\Permission;

class PermissionRequestTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public $itemColumn;
    public $itemUserColumn;
    public $itemAttributes;

    public function setUp(): void
    {
        parent::setUp();

        $this->setBaseRoute('example-permission.permissions');
        $this->setBaseModel(Permission::class);

        $attributes = [];

        $this->itemUserColumn = 'user_id';
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
    public function it_validate_store_permission_request($field, $formFields, $shouldPass = false)
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
        $attributes['name'] = 'old-name';
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

            'request_should_fail_when_no_name_is_not_unique' => [
                'field' => 'name',
                'data' => ['name' => 'old-name'],
                'passed' => false,
            ],

            'request_should_success_when_guard_name_is_filled_or_not' => [
                'field' => 'guard_name',
                'data' => ['guard_name' => ''],
                'passed' => true,
            ],


        ];
    }

    /**
     * @test
     * @dataProvider updateItemDataProvider
     */
    public function it_validate_update_permission_request($field, $formFields, $shouldPass = false)
    {
        $this->signIn();
        $this->beforeValidateUpdateRequest();

        $attributes = $this->itemAttributes;
        // $attributes[$this->itemUserColumn] = $this->user->id;
        $permission = $this->newItem($attributes);

        $response = $this->updateItem($permission->id, $this->getItemFields($formFields));

        if (!$shouldPass) {
            $response->assertSessionHasErrors([$field]);
        } else {
            $response->assertSessionHasNoErrors([$field]);
        }
    }

    public function beforeValidateUpdateRequest()
    {
        $attributes = $this->itemAttributes;
        $attributes['name'] = 'old-name';
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

            'request_should_fail_when_no_name_is_not_unique' => [
                'field' => 'name',
                'data' => ['name' => 'old-name'],
                'passed' => false,
            ],

            'request_should_success_when_guard_name_is_filled_or_not' => [
                'field' => 'guard_name',
                'data' => ['guard_name' => ''],
                'passed' => true,
            ],


        ];
    }

    /** @test */
    public function authenticated_user_can_create_permission()
    {
        $this->signIn();

        $attributes = $this->itemAttributes;
        // $attributes[$this->itemUserColumn] = $this->user->id;
        $attributes['name'] = 'name';
        $this->createItem($attributes);

        $model = new $this->base_model;
        // $this->assertDatabaseHas($model->getTable(), [$this->itemUserColumn => $this->user->id]);
        $this->assertDatabaseHas($model->getTable(), ['name' => 'name']);
    }

    /** @test */
    public function guest_cannot_create_permission()
    {
        $attributes = $this->itemAttributes;
        $response = $this->createItem($attributes);

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    /** @test */
    public function authenticated_user_can_update_permission()
    {
        $this->signIn();

        $attributes = $this->itemAttributes;
        // $attributes[$this->itemUserColumn] = $this->user->id;
        $permission = $this->newItem($attributes);
        $oldName = $permission->{$this->itemColumn};

        $this->updateItem($permission->id, $attributes);

        $permission->refresh();
        $model = new $this->base_model;
        $this->assertDatabaseHas($model->getTable(), [
            // $this->itemUserColumn => $this->user->id,
            $this->itemColumn => $permission->{$this->itemColumn},
        ]);
        $this->assertDatabaseMissing($model->getTable(), [
            // $this->itemUserColumn => $this->user->id,
            $this->itemColumn => $oldName,
        ]);
    }

    /** @test */
    // public function authenticated_user_cannot_update_others_permission()
    // {
    //     $this->signIn();
    //     $attributes = $this->itemAttributes;
    //     $other = $this->newItem($this->itemAttributes);

    //     $response = $this->updateItem($other->id);

    //     $response->assertStatus(403);
    // }
}
