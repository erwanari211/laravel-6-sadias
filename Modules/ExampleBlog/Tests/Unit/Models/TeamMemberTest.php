<?php

namespace Modules\ExampleBlog\tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\ExampleBlog\Models\TeamMember;
use App\User;

class TeamMemberTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public $itemColumn;
    public $itemUserColumn;
    public $itemAttributes;

    public function setUp(): void
    {
        parent::setUp();

        $attributes = [];

        $this->itemUserColumn = 'user_id';
        $this->itemColumn = 'name';
        $this->itemAttributes = $attributes;
    }

    /** @test */
    public function a_teamMember_belongs_to_a_user()
    {
        $user = create('App\User');
        $attributes = $this->itemAttributes;
        $attributes['user_id'] = $user->id;
        $teamMember = create(TeamMember::class, $attributes);
        $this->assertInstanceOf('App\User', $teamMember->user);
    }

    /** @test */
    public function a_teamMember_belongs_to_a_team()
    {
        $team = create('Modules\ExampleBlog\Models\Team');
        $attributes = $this->itemAttributes;
        $attributes['team_id'] = $team->id;
        $teamMember = create(TeamMember::class, $attributes);
        $this->assertInstanceOf('Modules\ExampleBlog\Models\Team', $teamMember->team);
    }



}
