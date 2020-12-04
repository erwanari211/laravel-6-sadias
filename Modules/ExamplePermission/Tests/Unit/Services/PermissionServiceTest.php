<?php

namespace Modules\ExamplePermission\Tests\Unit\Services;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Modules\ExamplePermission\Models\Permission;
use Modules\ExamplePermission\Services\PermissionService;

class PermissionServiceTest extends TestCase
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


    /** @test */
    public function it_can_fetch_users_permissions()
    {
        $service = new PermissionService;
        $this->signIn();
        $attributes = $this->itemAttributes;
        // $attributes[$this->itemUserColumn] = $this->user->id;
        $permission = $this->newItem($attributes);

        $permissions = $service->getData();

        $permissionsArray = $permissions->jsonSerialize();
        // $permission->load('owner');
        $permissionArray = $permission->toArray();
        // $this->assertContains($permissionArray, $permissionsArray);
        // $this->assertArrayHasKey('owner', $permissionsArray[0]);

        $this->assertCount(1, $permissionsArray);
        // $this->assertEquals($permissionsArray[0][$this->itemUserColumn], $permissionArray[$this->itemUserColumn]);
        // $this->assertEquals($permissionsArray[0][$this->itemUserColumn], $this->user->id);
        $this->assertEquals($permissionsArray[0][$this->itemColumn], $permissionArray[$this->itemColumn]);
    }

    /** @test */
    // public function it_cannot_fetch_others_permissions()
    // {
    //     $service = new PermissionService;
    //     $this->signIn();
    //     $attributes = $this->itemAttributes;
    //     // $attributes[$this->itemUserColumn] = $this->user->id;
    //     $permission = $this->newItem($attributes);
    //     // $permission->load('owner');

    //     $attributes = $this->itemAttributes;
    //     $otherpermission = $this->newItem($attributes);

    //     $permissions = $service->getData();

    //     $permissionsArray = $permissions->jsonSerialize();
    //     $permissionArray = $permission->toArray();
    //     $otherpermissionArray = $otherpermission->toArray();

    //     $this->assertContains($permissionArray, $permissionsArray);
    //     $this->assertNotContains($otherpermissionArray, $permissionsArray);

    //     $this->assertCount(1, $permissionsArray);
    //     $this->assertNotCount(2, $permissionsArray);
    // }

    /** @test */
    public function it_can_fetch_a_users_permission()
    {
        $service = new PermissionService;
        $this->signIn();
        $attributes = $this->itemAttributes;
        // $attributes[$this->itemUserColumn] = $this->user->id;
        $permission = $this->newItem($attributes);
        // $permission->load('owner');

        $item = $service->getItem($permission->id);

        $permissionArray = $permission->toArray();
        // $this->assertEquals($item[$this->itemUserColumn], $permissionArray[$this->itemUserColumn]);
        // $this->assertEquals($item[$this->itemUserColumn], $this->user->id);
        $this->assertEquals($item[$this->itemColumn], $permissionArray[$this->itemColumn]);
    }

    /** @test */
    public function it_can_create_permission()
    {
        $service = new PermissionService;
        $this->signIn();

        $attributes = $this->itemAttributes;
        // $attributes[$this->itemUserColumn] = $this->user->id;
        $newName = $this->faker->sentence;
        $attributes[$this->itemColumn] = $newName;

        $data = make($this->base_model, $attributes);

        $permission = $service->create($data->toArray());

        // $this->assertEquals($permission[$this->itemUserColumn], $this->user->id);
        $this->assertEquals($permission[$this->itemColumn], $newName);
        $model = new $this->base_model;
        $this->assertDatabaseHas($model->getTable(), [
            // $this->itemUserColumn => $this->user->id,
            $this->itemColumn => $newName
        ]);
    }

    /** @test */
    public function it_can_update_permission()
    {
        $service = new PermissionService;
        $this->signIn();

        $attributes = $this->itemAttributes;
        // $attributes[$this->itemUserColumn] = $this->user->id;
        $permission = $this->newItem($attributes);

        $attributes = $this->itemAttributes;
        // $attributes[$this->itemUserColumn] = $this->user->id;
        $newName = $this->faker->sentence;
        $attributes[$this->itemColumn] = $newName;

        $data = make($this->base_model, $attributes);
        $oldName = $permission->name;

        $permission = $service->update($permission, $data->toArray());

        $this->assertNotEquals($permission[$this->itemColumn], $oldName);
        $this->assertEquals($permission[$this->itemColumn], $newName);
        $model = new $this->base_model;
        $this->assertDatabaseHas($model->getTable(), [
            $this->itemColumn => $newName
        ]);
        $this->assertDatabaseMissing($model->getTable(), [
            $this->itemColumn => $oldName
        ]);
    }

    /** @test */
    public function it_can_delete_permission()
    {
        $model = new $this->base_model;
        $service = new PermissionService;
        $this->signIn();

        $attributes = $this->itemAttributes;
        // $attributes[$this->itemUserColumn] = $this->user->id;
        $permission = $this->newItem($attributes);
        $this->assertEquals(1, $model->all()->count());
        $oldName = $permission->{$this->itemColumn};

        $service->delete($permission);

        $this->assertEquals(0, $model->all()->count());
        $this->assertDatabaseMissing($model->getTable(), [
            $this->itemColumn => $oldName
        ]);
    }
}
