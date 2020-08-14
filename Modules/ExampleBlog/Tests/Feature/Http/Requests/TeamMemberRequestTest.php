<?php

namespace Modules\ExampleBlog\tests\Feature\Http\Requests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Modules\ExampleBlog\Models\Team;
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

        $user = create(User::class);
        $team = create(Team::class, ['owner_id' => $user->id]);
        $teamMember = create(TeamMember::class, [
            'user_id' => $user->id,
            'team_id' => $team->id,
            'role_name' => 'admin',
        ]);

        $attributes = [
            'team_id' => $team->id,
            'role_name' => 'admin',
        ];

        $this->itemUserColumn = 'user_id';
        $this->itemColumn = 'role_name';
        $this->itemAttributes = $attributes;

        $this->user = $user;
        $this->team = $team;
        $this->teamMember = $teamMember;
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
        $user = create(User::class, ['email' => 'newUser@app.com']);
        $this->newUser = $user;

        $this->signIn($this->user);
        $this->beforeValidateStoreRequest();

        $response = $this->createItem($this->getItemFields($formFields), [$this->team->id]);

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

            'request_should_fail_when_no_email_is_provided' => [
                'field' => 'email',
                'data' => ['email' => ''],
                'passed' => false,
            ],
            'request_should_fail_when_invalid_email_is_provided' => [
                'field' => 'email',
                'data' => ['email' => 'nottvalid-email'],
                'passed' => false,
            ],
            'request_should_fail_when_email_not_exists_in_table' => [
                'field' => 'email',
                'data' => ['email' => 'randomemail@app.com'],
                'passed' => false,
            ],
            'request_should_success_when_email_is_provided_and_exists_in_table' => [
                'field' => 'email',
                'data' => ['email' => 'newUser@app.com'],
                'passed' => true,
            ],

            'request_should_fail_when_no_role_name_is_provided' => [
                'field' => 'role_name',
                'data' => ['role_name' => '', 'email' => 'newUser@app.com'],
                'passed' => false,
            ],
            'request_should_success_when_role_name_is_provided' => [
                'field' => 'role_name',
                'data' => ['role_name' => 'admin', 'email' => 'newUser@app.com'],
                'passed' => true,
            ],

            'request_should_fail_when_no_is_active_is_provided' => [
                'field' => 'is_active',
                'data' => ['is_active' => '', 'email' => 'newUser@app.com'],
                'passed' => false,
            ],
            'request_should_success_when_is_active_is_provided' => [
                'field' => 'is_active',
                'data' => ['is_active' => '1', 'email' => 'newUser@app.com'],
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
        $user = create(User::class, ['email' => 'newUser@app.com']);
        $this->newUser = $user;

        $this->signIn($this->user);
        $this->beforeValidateUpdateRequest();

        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $attributes['team_id'] = $this->team->id;
        $teamMember = $this->newItem($attributes);

        $response = $this->updateItem([$this->team->id, $teamMember->id], $this->getItemFields($formFields));

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

            'request_should_fail_when_no_role_name_is_provided' => [
                'field' => 'role_name',
                'data' => ['role_name' => ''],
                'passed' => false,
            ],
            'request_should_success_when_role_name_is_provided' => [
                'field' => 'role_name',
                'data' => ['role_name' => 'admin'],
                'passed' => true,
            ],

            'request_should_fail_when_no_is_active_is_provided' => [
                'field' => 'is_active',
                'data' => ['is_active' => '', 'role_name' => 'admin'],
                'passed' => false,
            ],
            'request_should_success_when_is_active_is_provided' => [
                'field' => 'is_active',
                'data' => ['is_active' => '1', 'role_name' => 'admin'],
                'passed' => true,
            ],

        ];
    }

    /** @test */
    public function authenticated_user_can_create_teamMember()
    {
        $this->signIn($this->user);

        $attributes = $this->itemAttributes;
        $newUser = create(User::class);
        $attributes[$this->itemUserColumn] = $newUser->id;
        $this->createItem($attributes, $this->team->id);

        $model = new $this->base_model;
        $this->assertDatabaseHas($model->getTable(), [$this->itemUserColumn => $this->user->id]);
    }

    /** @test */
    public function guest_cannot_create_teamMember()
    {
        $attributes = $this->itemAttributes;
        $response = $this->createItem($attributes, $this->team->id);

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    /** @test */
    public function authenticated_user_can_update_teamMember()
    {
        $this->signIn($this->user);

        $attributes = $this->itemAttributes;
        $newUser = create(User::class);
        $attributes[$this->itemUserColumn] = $newUser->id;
        $teamMember = $this->newItem($attributes);
        $oldName = $teamMember->{$this->itemColumn};
        $attributes['role_name'] = 'editor';

        $this->updateItem([$this->team->id, $teamMember->id], $attributes);

        $teamMember->refresh();
        $model = new $this->base_model;
        $this->assertDatabaseHas($model->getTable(), [
            $this->itemUserColumn => $newUser->id,
            $this->itemColumn => $teamMember->{$this->itemColumn},
        ]);
        $this->assertDatabaseMissing($model->getTable(), [
            $this->itemUserColumn => $newUser->id,
            $this->itemColumn => $oldName,
        ]);
    }

    /** @test */
    public function authenticated_user_cannot_update_others_teamMember()
    {
        $this->signIn($this->user);
        $attributes = $this->itemAttributes;
        $otherTeam = create(Team::class);
        $attributes['team_id'] = $otherTeam->id;
        $other = $this->newItem($attributes);

        $response = $this->updateItem([$otherTeam->id, $other->id]);

        $response->assertStatus(403);
    }
}
