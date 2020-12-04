<?php

namespace Modules\ExamplePermission\Tests\Feature\Http\Requests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
// use Modules\ExamplePermission\Models\User;

class UserRequestTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public $itemColumn;
    public $itemUserColumn;
    public $itemAttributes;

    public function setUp(): void
    {
        parent::setUp();

        $this->setBaseRoute('example-permission.users');
        $this->setBaseModel(User::class);

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
    public function it_validate_store_user_request($field, $formFields, $shouldPass = false)
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
        $attributes['email'] = 'old-email@app.com';
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
                'data' => [
                    'name' => 'name',
                    'password' => 12345678,
                    'password_confirmation' => 12345678,
                ],
                'passed' => true,
            ],

            'request_should_fail_when_no_email_is_provided' => [
                'field' => 'email',
                'data' => ['email' => ''],
                'passed' => false,
            ],
            'request_should_success_when_email_is_provided' => [
                'field' => 'email',
                'data' => [
                    'email' => 'email',
                    'password' => 12345678,
                    'password_confirmation' => 12345678,
                ],
                'passed' => true,
            ],

            'request_should_fail_when_no_email_is_not_unique' => [
                'field' => 'email',
                'data' => ['email' => 'old-email@app.com'],
                'passed' => false,
            ],

            'request_should_fail_when_no_password_is_provided' => [
                'field' => 'password',
                'data' => ['password' => ''],
                'passed' => false,
            ],
            'request_should_success_when_password_is_provided' => [
                'field' => 'password',
                'data' => [
                    'password' => 12345678,
                    'password_confirmation' => 12345678,
                ],
                'passed' => true,
            ],

            'request_should_fail_when_no_password_confirmation_is_provided' => [
                'field' => 'password',
                'data' => [
                    'password' => 12345678,
                    'password_confirmation' => '',
                ],
                'passed' => false,
            ],
            'request_should_success_when_password_confirmation_is_provided' => [
                'field' => 'password_confirmation',
                'data' => [
                    'password' => 12345678,
                    'password_confirmation' => 12345678,
                ],
                'passed' => true,
            ],


        ];
    }

    /**
     * @test
     * @dataProvider updateItemDataProvider
     */
    public function it_validate_update_user_request($field, $formFields, $shouldPass = false)
    {
        $this->signIn();
        $this->beforeValidateUpdateRequest();

        $attributes = $this->itemAttributes;
        // $attributes[$this->itemUserColumn] = $this->user->id;
        $user = $this->newItem($attributes);

        $response = $this->updateItem($user->id, $this->getItemFields($formFields));

        if (!$shouldPass) {
            $response->assertSessionHasErrors([$field]);
        } else {
            $response->assertSessionHasNoErrors([$field]);
        }
    }

    public function beforeValidateUpdateRequest()
    {
        $attributes = $this->itemAttributes;
        $attributes['email'] = 'old-email@app.com';
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
                'data' => [
                    'name' => 'name',
                    'password' => 12345678,
                    'password_confirmation' => 12345678,
                ],
                'passed' => true,
            ],

            'request_should_fail_when_no_email_is_provided' => [
                'field' => 'email',
                'data' => ['email' => ''],
                'passed' => false,
            ],
            'request_should_success_when_email_is_provided' => [
                'field' => 'email',
                'data' => [
                    'email' => 'email',
                    'password' => 12345678,
                    'password_confirmation' => 12345678,
                ],
                'passed' => true,
            ],

            'request_should_fail_when_no_email_is_not_unique' => [
                'field' => 'email',
                'data' => ['email' => 'old-email@app.com'],
                'passed' => false,
            ],

            'request_should_success_when_no_password_is_provided' => [
                'field' => 'password',
                'data' => [
                    'password' => '',
                    'password_confirmation' => 12345678,
                ],
                'passed' => true,
            ],
            'request_should_success_when_password_is_provided' => [
                'field' => 'password',
                'data' => [
                    'password' => 12345678,
                    'password_confirmation' => 12345678,
                ],
                'passed' => true,
            ],

            'request_should_fail_when_no_password_confirmation_is_provided' => [
                'field' => 'password',
                'data' => [
                    'password' => 12345678,
                    'password_confirmation' => '',
                ],
                'passed' => false,
            ],
            'request_should_success_when_password_confirmation_is_provided' => [
                'field' => 'password_confirmation',
                'data' => [
                    'password' => 12345678,
                    'password_confirmation' => 12345678,
                ],
                'passed' => true,
            ],


        ];
    }

    /** @test */
    public function authenticated_user_can_create_user()
    {
        $this->signIn();

        $attributes = $this->itemAttributes;
        $attributes['name'] = 'newname';
        $attributes['password'] = '12345678';
        $attributes['password_confirmation'] = '12345678';
        // $attributes[$this->itemUserColumn] = $this->user->id;
        $res = $this->createItem($attributes);

        $model = new $this->base_model;
        // $this->assertDatabaseHas($model->getTable(), [$this->itemUserColumn => $this->user->id]);
        $this->assertDatabaseHas($model->getTable(), ['name' => $attributes['name']]);
    }

    /** @test */
    public function guest_cannot_create_user()
    {
        $attributes = $this->itemAttributes;
        $response = $this->createItem($attributes);

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    /** @test */
    public function authenticated_user_can_update_user()
    {
        $this->signIn();

        $attributes = $this->itemAttributes;
        // $attributes[$this->itemUserColumn] = $this->user->id;
        $user = $this->newItem($attributes);
        $oldName = $user->{$this->itemColumn};

        $attributes['name'] = 'newname';
        $attributes['password'] = '12345678';
        $attributes['password_confirmation'] = '12345678';
        $response = $this->updateItem($user->id, $attributes);

        $user->refresh();
        $model = new $this->base_model;
        $this->assertDatabaseHas($model->getTable(), [
            // $this->itemUserColumn => $this->user->id,
            $this->itemColumn => $user->{$this->itemColumn},
        ]);
        $this->assertDatabaseMissing($model->getTable(), [
            // $this->itemUserColumn => $this->user->id,
            $this->itemColumn => $oldName,
        ]);
    }

    /** @test */
    // public function authenticated_user_cannot_update_others_user()
    // {
    //     $this->signIn();
    //     $attributes = $this->itemAttributes;
    //     $other = $this->newItem($this->itemAttributes);

    //     $response = $this->updateItem($other->id);

    //     $response->assertStatus(403);
    // }
}
