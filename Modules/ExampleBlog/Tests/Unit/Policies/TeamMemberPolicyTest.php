<?php

namespace Modules\ExampleBlog\tests\Unit\Policies;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Modules\ExampleBlog\Models\TeamMember;

class TeamMemberPolicyTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public $itemColumn;
    public $itemUserColumn;
    public $itemAttributes;

    public function setUp(): void
    {
        parent::setUp();

        $this->setBaseRoute('example.blog.backend.channels');
        $this->setBaseModel(TeamMember::class);

        $attributes = [];

        $this->itemUserColumn = 'user_id';
        $this->itemColumn = 'name';
        $this->itemAttributes = $attributes;
    }

    /** @test */
    public function owner_can_create_teamMember()
    {
        $this->signIn();
        $teamMember = new $this->base_model;
        $this->assertTrue($this->user->can('create', $teamMember));
    }

    /** @test */
    public function owner_can_read_teamMember()
    {
        $this->signIn();
        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $teamMember = $this->newItem($attributes);
        $this->assertTrue($this->user->can('view', $teamMember));
    }

    /** @test */
    public function owner_can_update_teamMember()
    {
        $this->signIn();
        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $teamMember = $this->newItem($attributes);
        $this->assertTrue($this->user->can('update', $teamMember));
    }

    /** @test */
    public function owner_can_delete_teamMember()
    {
        $this->signIn();
        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $teamMember = $this->newItem($attributes);
        $this->assertTrue($this->user->can('delete', $teamMember));
    }

    /** @test */
    public function user_cannot_access_others_teamMember()
    {
        $this->signIn();
        $attributes = $this->itemAttributes;
        $teamMember = $this->newItem($attributes);
        $this->assertFalse($this->user->can('view', $teamMember));
        $this->assertFalse($this->user->can('update', $teamMember));
        $this->assertFalse($this->user->can('delete', $teamMember));
    }
}
