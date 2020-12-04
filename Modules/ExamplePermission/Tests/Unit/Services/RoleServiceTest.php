<?php

namespace Modules\ExamplePermission\Tests\Unit\Services;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Modules\ExamplePermission\Models\Role;
use Modules\ExamplePermission\Services\RoleService;

class RoleServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public $itemColumn;
    public $itemUserColumn;
    public $itemAttributes;

    public function setUp(): void
    {
        parent::setUp();

        $this->setBaseRoute('example-permission.roles');
        $this->setBaseModel(Role::class);

        $attributes = [];

        $this->itemUserColumn = 'user_id';
        $this->itemColumn = 'name';
        $this->itemAttributes = $attributes;
    }


    /** @test */
    public function it_can_fetch_users_roles()
    {
        $service = new RoleService;
        $this->signIn();
        $attributes = $this->itemAttributes;
        // $attributes[$this->itemUserColumn] = $this->user->id;
        $role = $this->newItem($attributes);

        $roles = $service->getData();

        $rolesArray = $roles->jsonSerialize();
        // $role->load('owner');
        $roleArray = $role->toArray();
        // $this->assertContains($roleArray, $rolesArray);
        // $this->assertArrayHasKey('owner', $rolesArray[0]);

        $this->assertCount(1, $rolesArray);
        // $this->assertEquals($rolesArray[0][$this->itemUserColumn], $roleArray[$this->itemUserColumn]);
        // $this->assertEquals($rolesArray[0][$this->itemUserColumn], $this->user->id);
        $this->assertEquals($rolesArray[0][$this->itemColumn], $roleArray[$this->itemColumn]);
    }

    /** @test */
    // public function it_cannot_fetch_others_roles()
    // {
    //     $service = new RoleService;
    //     $this->signIn();
    //     $attributes = $this->itemAttributes;
    //     // $attributes[$this->itemUserColumn] = $this->user->id;
    //     $role = $this->newItem($attributes);
    //     // $role->load('owner');

    //     $attributes = $this->itemAttributes;
    //     $otherrole = $this->newItem($attributes);

    //     $roles = $service->getData();

    //     $rolesArray = $roles->jsonSerialize();
    //     $roleArray = $role->toArray();
    //     $otherroleArray = $otherrole->toArray();

    //     $this->assertContains($roleArray, $rolesArray);
    //     $this->assertNotContains($otherroleArray, $rolesArray);

    //     $this->assertCount(1, $rolesArray);
    //     $this->assertNotCount(2, $rolesArray);
    // }

    /** @test */
    public function it_can_fetch_a_users_role()
    {
        $service = new RoleService;
        $this->signIn();
        $attributes = $this->itemAttributes;
        // $attributes[$this->itemUserColumn] = $this->user->id;
        $role = $this->newItem($attributes);
        // $role->load('owner');

        $item = $service->getItem($role->id);

        $roleArray = $role->toArray();
        // $this->assertEquals($item[$this->itemUserColumn], $roleArray[$this->itemUserColumn]);
        // $this->assertEquals($item[$this->itemUserColumn], $this->user->id);
        $this->assertEquals($item[$this->itemColumn], $roleArray[$this->itemColumn]);
    }

    /** @test */
    public function it_can_create_role()
    {
        $service = new RoleService;
        $this->signIn();

        $attributes = $this->itemAttributes;
        // $attributes[$this->itemUserColumn] = $this->user->id;
        $newName = $this->faker->sentence;
        $attributes[$this->itemColumn] = $newName;

        $data = make($this->base_model, $attributes);

        $role = $service->create($data->toArray());

        // $this->assertEquals($role[$this->itemUserColumn], $this->user->id);
        $this->assertEquals($role[$this->itemColumn], $newName);
        $model = new $this->base_model;
        $this->assertDatabaseHas($model->getTable(), [
            // $this->itemUserColumn => $this->user->id,
            $this->itemColumn => $newName
        ]);
    }

    /** @test */
    public function it_can_update_role()
    {
        $service = new RoleService;
        $this->signIn();

        $attributes = $this->itemAttributes;
        // $attributes[$this->itemUserColumn] = $this->user->id;
        $role = $this->newItem($attributes);

        $attributes = $this->itemAttributes;
        // $attributes[$this->itemUserColumn] = $this->user->id;
        $newName = $this->faker->sentence;
        $attributes[$this->itemColumn] = $newName;

        $data = make($this->base_model, $attributes);
        $oldName = $role->name;

        $role = $service->update($role, $data->toArray());

        $this->assertNotEquals($role[$this->itemColumn], $oldName);
        $this->assertEquals($role[$this->itemColumn], $newName);
        $model = new $this->base_model;
        $this->assertDatabaseHas($model->getTable(), [
            $this->itemColumn => $newName
        ]);
        $this->assertDatabaseMissing($model->getTable(), [
            $this->itemColumn => $oldName
        ]);
    }

    /** @test */
    public function it_can_delete_role()
    {
        $model = new $this->base_model;
        $service = new RoleService;
        $this->signIn();

        $attributes = $this->itemAttributes;
        // $attributes[$this->itemUserColumn] = $this->user->id;
        $role = $this->newItem($attributes);
        $this->assertEquals(1, $model->all()->count());
        $oldName = $role->{$this->itemColumn};

        $service->delete($role);

        $this->assertEquals(0, $model->all()->count());
        $this->assertDatabaseMissing($model->getTable(), [
            $this->itemColumn => $oldName
        ]);
    }
}
