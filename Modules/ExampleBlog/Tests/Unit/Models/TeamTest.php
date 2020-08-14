<?php

namespace Modules\ExampleBlog\tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\ExampleBlog\Models\Team;
use App\User;
use Modules\ExampleBlog\Models\TeamMember;

class TeamTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public $itemColumn;
    public $itemUserColumn;
    public $itemAttributes;

    public function setUp(): void
    {
        parent::setUp();

        $attributes = [];

        $this->itemUserColumn = 'owner_id';
        $this->itemColumn = 'name';
        $this->itemAttributes = $attributes;
    }

    /** @test */
    public function a_team_belongs_to_a_owner()
    {
        $owner = create('App\User');
        $attributes = $this->itemAttributes;
        $attributes['owner_id'] = $owner->id;
        $team = create(Team::class, $attributes);
        $this->assertInstanceOf('App\User', $team->owner);
    }

    /** @test */
    public function a_team_has_any_team_members()
    {
        $attributes = $this->itemAttributes;
        $team = create(Team::class, $attributes);

        $teamMember = create(TeamMember::class, ['team_id' => $team->id]);
        $member = $team->teamMembers()->first();

        $this->assertInstanceOf(TeamMember::class, $member);
    }



}
