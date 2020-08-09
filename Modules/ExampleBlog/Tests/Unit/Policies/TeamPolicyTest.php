<?php

namespace Modules\ExampleBlog\tests\Unit\Policies;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Modules\ExampleBlog\Models\Team;

class TeamPolicyTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public $itemColumn;
    public $itemUserColumn;
    public $itemAttributes;

    public function setUp(): void
    {
        parent::setUp();

        $this->setBaseRoute('example.blog.backend.channels');
        $this->setBaseModel(Team::class);

        $attributes = [];

        $this->itemUserColumn = 'owner_id';
        $this->itemColumn = 'name';
        $this->itemAttributes = $attributes;
    }

    /** @test */
    public function owner_can_create_team()
    {
        $this->signIn();
        $team = new $this->base_model;
        $this->assertTrue($this->user->can('create', $team));
    }

    /** @test */
    public function owner_can_read_team()
    {
        $this->signIn();
        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $team = $this->newItem($attributes);
        $this->assertTrue($this->user->can('view', $team));
    }

    /** @test */
    public function owner_can_update_team()
    {
        $this->signIn();
        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $team = $this->newItem($attributes);
        $this->assertTrue($this->user->can('update', $team));
    }

    /** @test */
    public function owner_can_delete_team()
    {
        $this->signIn();
        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $team = $this->newItem($attributes);
        $this->assertTrue($this->user->can('delete', $team));
    }

    /** @test */
    public function user_cannot_access_others_team()
    {
        $this->signIn();
        $attributes = $this->itemAttributes;
        $team = $this->newItem($attributes);
        $this->assertFalse($this->user->can('view', $team));
        $this->assertFalse($this->user->can('update', $team));
        $this->assertFalse($this->user->can('delete', $team));
    }
}
