<?php

namespace Modules\ExampleBlog\tests\Unit\Policies;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Modules\ExampleBlog\Models\TeamMember;
use Modules\ExampleBlog\Models\Team;

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
        $this->itemColumn = 'name';
        $this->itemAttributes = $attributes;

        $this->user = $user;
        $this->team = $team;
        $this->teamMember = $teamMember;
    }

    /** @test */
    public function owner_can_create_teamMember()
    {
        $this->signIn($this->user);
        $teamMember = new $this->base_model;
        $this->assertTrue($this->user->can('create', $teamMember));
    }

    /** @test */
    public function owner_can_read_teamMember()
    {
        $this->signIn($this->user);
        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $teamMember = $this->newItem($attributes);
        $this->assertTrue($this->user->can('view', $teamMember));
    }

    /** @test */
    public function owner_can_update_teamMember()
    {
        $this->signIn($this->user);
        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $teamMember = $this->newItem($attributes);
        $this->assertTrue($this->user->can('update', $teamMember));
    }

    /** @test */
    public function owner_can_delete_teamMember()
    {
        $this->signIn($this->user);
        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $teamMember = $this->newItem($attributes);
        $this->assertTrue($this->user->can('delete', $teamMember));
    }

    /** @test */
    public function user_cannot_access_others_teamMember()
    {
        $this->signIn($this->user);
        $attributes = $this->itemAttributes;
        $otherTeam = create(Team::class);
        $attributes['team_id'] = $otherTeam->id;
        $teamMember = $this->newItem($attributes);
        $this->assertFalse($this->user->can('view', $teamMember));
        $this->assertFalse($this->user->can('update', $teamMember));
        $this->assertFalse($this->user->can('delete', $teamMember));
    }
}
