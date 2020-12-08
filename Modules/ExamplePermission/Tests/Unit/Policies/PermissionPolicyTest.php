<?php

namespace Modules\ExamplePermission\Tests\Unit\Policies;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Modules\ExamplePermission\Models\Permission;

class PermissionPolicyTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public $itemColumn;
    public $itemUserColumn;
    public $itemAttributes;

    public function setUp(): void
    {
        parent::setUp();

        $this->setBaseRoute('example.blog.backend.channels');
        $this->setBaseModel(Permission::class);

        $attributes = [];

        $this->itemUserColumn = 'user_id';
        $this->itemColumn = 'name';
        $this->itemAttributes = $attributes;
    }

    /** @test */
    public function owner_can_create_permission()
    {
        $this->signIn();
        $permission = new $this->base_model;
        $this->assertTrue($this->user->can('create', $permission));
    }

    /** @test */
    public function owner_can_read_permission()
    {
        $this->signIn();
        $attributes = $this->itemAttributes;
        // $attributes[$this->itemUserColumn] = $this->user->id;
        $permission = $this->newItem($attributes);
        $this->assertTrue($this->user->can('view', $permission));
    }

    /** @test */
    public function owner_can_update_permission()
    {
        $this->signIn();
        $attributes = $this->itemAttributes;
        // $attributes[$this->itemUserColumn] = $this->user->id;
        $permission = $this->newItem($attributes);
        $this->assertTrue($this->user->can('update', $permission));
    }

    /** @test */
    public function owner_can_delete_permission()
    {
        $this->signIn();
        $attributes = $this->itemAttributes;
        // $attributes[$this->itemUserColumn] = $this->user->id;
        $permission = $this->newItem($attributes);
        $this->assertTrue($this->user->can('delete', $permission));
    }

    /** @test */
    // public function user_cannot_access_others_permission()
    // {
    //     $this->signIn();
    //     $attributes = $this->itemAttributes;
    //     $permission = $this->newItem($attributes);
    //     $this->assertFalse($this->user->can('view', $permission));
    //     $this->assertFalse($this->user->can('update', $permission));
    //     $this->assertFalse($this->user->can('delete', $permission));
    // }
}
