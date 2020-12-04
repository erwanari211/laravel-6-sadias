<?php

namespace Modules\ExamplePermission\Tests\Unit\Policies;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Modules\ExamplePermission\Models\Role;

class RolePolicyTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public $itemColumn;
    public $itemUserColumn;
    public $itemAttributes;

    public function setUp(): void
    {
        parent::setUp();

        $this->setBaseRoute('example.blog.backend.channels');
        $this->setBaseModel(Role::class);

        $attributes = [];

        $this->itemUserColumn = 'user_id';
        $this->itemColumn = 'name';
        $this->itemAttributes = $attributes;
    }

    /** @test */
    public function owner_can_create_role()
    {
        $this->signIn();
        $role = new $this->base_model;
        $this->assertTrue($this->user->can('create', $role));
    }

    /** @test */
    public function owner_can_read_role()
    {
        $this->signIn();
        $attributes = $this->itemAttributes;
        // $attributes[$this->itemUserColumn] = $this->user->id;
        $role = $this->newItem($attributes);
        $this->assertTrue($this->user->can('view', $role));
    }

    /** @test */
    public function owner_can_update_role()
    {
        $this->signIn();
        $attributes = $this->itemAttributes;
        // $attributes[$this->itemUserColumn] = $this->user->id;
        $role = $this->newItem($attributes);
        $this->assertTrue($this->user->can('update', $role));
    }

    /** @test */
    public function owner_can_delete_role()
    {
        $this->signIn();
        $attributes = $this->itemAttributes;
        // $attributes[$this->itemUserColumn] = $this->user->id;
        $role = $this->newItem($attributes);
        $this->assertTrue($this->user->can('delete', $role));
    }

    /** @test */
    // public function user_cannot_access_others_role()
    // {
    //     $this->signIn();
    //     $attributes = $this->itemAttributes;
    //     $role = $this->newItem($attributes);
    //     $this->assertFalse($this->user->can('view', $role));
    //     $this->assertFalse($this->user->can('update', $role));
    //     $this->assertFalse($this->user->can('delete', $role));
    // }
}
