<?php

namespace Modules\ExamplePermission\Tests\Unit\Services;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
// use Modules\ExamplePermission\Models\User;
use Modules\ExamplePermission\Services\UserService;

class UserServiceTest extends TestCase
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


    /** @test */
    public function it_can_fetch_users_users()
    {
        $service = new UserService;
        $this->signIn();
        $attributes = $this->itemAttributes;
        // $attributes[$this->itemUserColumn] = $this->user->id;
        $user = $this->newItem($attributes);

        $users = $service->getData();

        $usersArray = $users->jsonSerialize();
        // $user->load('owner');
        $userArray = $user->toArray();
        // $this->assertContains($userArray, $usersArray);
        // $this->assertArrayHasKey('owner', $usersArray[0]);

        $this->assertCount(1 + 1, $usersArray);
        // $this->assertEquals($usersArray[0][$this->itemUserColumn], $userArray[$this->itemUserColumn]);
        // $this->assertEquals($usersArray[0][$this->itemUserColumn], $this->user->id);
        $this->assertEquals($usersArray[0 + 1][$this->itemColumn], $userArray[$this->itemColumn]);
    }

    /** @test */
    // public function it_cannot_fetch_others_users()
    // {
    //     $service = new UserService;
    //     $this->signIn();
    //     $attributes = $this->itemAttributes;
    //     $attributes[$this->itemUserColumn] = $this->user->id;
    //     $user = $this->newItem($attributes);
    //     // $user->load('owner');

    //     $attributes = $this->itemAttributes;
    //     $otheruser = $this->newItem($attributes);

    //     $users = $service->getData();

    //     $usersArray = $users->jsonSerialize();
    //     $userArray = $user->toArray();
    //     $otheruserArray = $otheruser->toArray();

    //     $this->assertContains($userArray, $usersArray);
    //     $this->assertNotContains($otheruserArray, $usersArray);

    //     $this->assertCount(1, $usersArray);
    //     $this->assertNotCount(2, $usersArray);
    // }

    /** @test */
    public function it_can_fetch_a_users_user()
    {
        $service = new UserService;
        $this->signIn();
        $attributes = $this->itemAttributes;
        // $attributes[$this->itemUserColumn] = $this->user->id;
        $user = $this->newItem($attributes);
        // $user->load('owner');

        $item = $service->getItem($user->id);

        $userArray = $user->toArray();
        // $this->assertEquals($item[$this->itemUserColumn], $userArray[$this->itemUserColumn]);
        // $this->assertEquals($item[$this->itemUserColumn], $this->user->id);
        $this->assertEquals($item[$this->itemColumn], $userArray[$this->itemColumn]);
    }

    /** @test */
    public function it_can_create_user()
    {
        $service = new UserService;
        $this->signIn();

        $attributes = $this->itemAttributes;
        // $attributes[$this->itemUserColumn] = $this->user->id;
        $newName = $this->faker->sentence;
        $attributes[$this->itemColumn] = $newName;

        $data = make($this->base_model, $attributes);
        $userData = $data->toArray();
        $userData['password'] = 12345678;

        $user = $service->create($userData);

        // $this->assertEquals($user[$this->itemUserColumn], $this->user->id);
        $this->assertEquals($user[$this->itemColumn], $newName);
        $model = new $this->base_model;
        $this->assertDatabaseHas($model->getTable(), [
            // $this->itemUserColumn => $this->user->id,
            $this->itemColumn => $newName
        ]);
    }

    /** @test */
    public function it_can_update_user()
    {
        $service = new UserService;
        $this->signIn();

        $attributes = $this->itemAttributes;
        // $attributes[$this->itemUserColumn] = $this->user->id;
        $user = $this->newItem($attributes);

        $attributes = $this->itemAttributes;
        // $attributes[$this->itemUserColumn] = $this->user->id;
        $newName = $this->faker->sentence;
        $attributes[$this->itemColumn] = $newName;

        $data = make($this->base_model, $attributes);
        $oldName = $user->name;

        $user = $service->update($user, $data->toArray());

        $this->assertNotEquals($user[$this->itemColumn], $oldName);
        $this->assertEquals($user[$this->itemColumn], $newName);
        $model = new $this->base_model;
        $this->assertDatabaseHas($model->getTable(), [
            $this->itemColumn => $newName
        ]);
        $this->assertDatabaseMissing($model->getTable(), [
            $this->itemColumn => $oldName
        ]);
    }

    /** @test */
    public function it_can_delete_user()
    {
        $model = new $this->base_model;
        $service = new UserService;
        $this->signIn();

        $attributes = $this->itemAttributes;
        // $attributes[$this->itemUserColumn] = $this->user->id;
        $user = $this->newItem($attributes);
        $this->assertEquals(1 + 1, $model->all()->count());
        $oldName = $user->{$this->itemColumn};

        $service->delete($user);

        $this->assertEquals(0 + 1, $model->all()->count());
        $this->assertDatabaseMissing($model->getTable(), [
            $this->itemColumn => $oldName
        ]);
    }
}
