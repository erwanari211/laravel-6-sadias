<?php

namespace Modules\ExampleBlog\tests\Unit\Services;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Modules\ExampleBlog\Models\Team;
use Modules\ExampleBlog\Models\TeamMember;
use Modules\ExampleBlog\Services\TeamMemberService;

class TeamMemberServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public $itemColumn;
    public $itemUserColumn;
    public $itemAttributes;

    public function setUp(): void
    {
        parent::setUp();

        $this->setBaseRoute('example-blog.team-members');
        $this->setBaseModel(TeamMember::class);

        $attributes = [];

        $this->itemUserColumn = 'user_id';
        $this->itemColumn = 'role_name';
        $this->itemAttributes = $attributes;
    }


    /** @test */
    public function it_can_fetch_users_teamMembers()
    {
        $this->signIn();
        $team = create(Team::class, ['owner_id' => $this->user->id]);
        $service = new TeamMemberService($team);

        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $attributes['team_id'] = $team->id;
        $teamMember = $this->newItem($attributes);

        $teamMembers = $service->getData();

        $teamMembersArray = $teamMembers->jsonSerialize();
        // $teamMember->load('owner');
        $teamMemberArray = $teamMember->toArray();
        // $this->assertContains($teamMemberArray, $teamMembersArray);
        // $this->assertArrayHasKey('owner', $teamMembersArray[0]);

        $this->assertCount(1, $teamMembersArray);
        $this->assertEquals($teamMembersArray[0][$this->itemUserColumn], $teamMemberArray[$this->itemUserColumn]);
        $this->assertEquals($teamMembersArray[0][$this->itemUserColumn], $this->user->id);
        $this->assertEquals($teamMembersArray[0][$this->itemColumn], $teamMemberArray[$this->itemColumn]);
    }

    /** @test */
    public function it_cannot_fetch_others_teamMembers()
    {
        $this->signIn();
        $team = create(Team::class, ['owner_id' => $this->user->id]);
        $service = new TeamMemberService($team);

        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $attributes['team_id'] = $team->id;
        $teamMember = $this->newItem($attributes);
        // $teamMember->load('owner');

        $attributes = $this->itemAttributes;
        $otherteamMember = $this->newItem($attributes);

        $teamMembers = $service->getData($team);

        $teamMembersArray = $teamMembers->jsonSerialize();
        $teamMemberArray = $teamMember->toArray();
        $otherteamMemberArray = $otherteamMember->toArray();

        // $this->assertContains($teamMemberArray, $teamMembersArray);
        $this->assertNotContains($otherteamMemberArray, $teamMembersArray);

        $this->assertCount(1, $teamMembersArray);
        $this->assertNotCount(2, $teamMembersArray);
    }

    /** @test */
    public function it_can_fetch_a_users_teamMember()
    {
        $this->signIn();
        $team = create(Team::class, ['owner_id' => $this->user->id]);
        $service = new TeamMemberService($team);

        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $teamMember = $this->newItem($attributes);
        // $teamMember->load('owner');

        $item = $service->getItem($teamMember->id);

        $teamMemberArray = $teamMember->toArray();
        $this->assertEquals($item[$this->itemUserColumn], $teamMemberArray[$this->itemUserColumn]);
        $this->assertEquals($item[$this->itemUserColumn], $this->user->id);
        $this->assertEquals($item[$this->itemColumn], $teamMemberArray[$this->itemColumn]);
    }

    /** @test */
    public function it_can_create_teamMember()
    {
        $this->signIn();
        $team = create(Team::class, ['owner_id' => $this->user->id]);
        $service = new TeamMemberService($team);

        $newMember = create(User::class);

        $attributes = $this->itemAttributes;
        $attributes['team_id'] = $team->id;
        $attributes['email'] = $newMember->email;
        $attributes['role_name'] = 'author';
        $data = make($this->base_model, $attributes);

        $teamMember = $service->create($data->toArray());
        $this->assertEquals($teamMember[$this->itemUserColumn], $newMember->id);
        $this->assertEquals($teamMember[$this->itemColumn], 'author');
        $model = new $this->base_model;
        $this->assertDatabaseHas($model->getTable(), [
            $this->itemUserColumn => $newMember->id,
            $this->itemColumn => 'author'
        ]);
    }

    /** @test */
    public function it_can_update_teamMember()
    {
        $this->signIn();
        $team = create(Team::class, ['owner_id' => $this->user->id]);
        $service = new TeamMemberService($team);

        $newMember = create(User::class);

        $oldRole = 'author';
        $attributes = $this->itemAttributes;
        $attributes['team_id'] = $team->id;
        $attributes[$this->itemColumn] = $oldRole;
        $attributes['user_id'] = $newMember->id;
        $data = make($this->base_model, $attributes);

        $teamMember = $this->newItem($attributes);

        $newRole = 'editor';
        $attributes[$this->itemColumn] = $newRole;
        $data = make($this->base_model, $attributes);
        $teamMember = $service->update($teamMember, $data->toArray());

        $this->assertNotEquals($teamMember[$this->itemColumn], $oldRole);
        $this->assertEquals($teamMember[$this->itemColumn], $newRole);
        $model = new $this->base_model;
        $this->assertDatabaseHas($model->getTable(), [
            $this->itemColumn => $newRole
        ]);
        $this->assertDatabaseMissing($model->getTable(), [
            $this->itemColumn => $oldRole
        ]);
    }

    /** @test */
    public function it_can_delete_teamMember()
    {
        $this->signIn();
        $team = create(Team::class, ['owner_id' => $this->user->id]);
        $service = new TeamMemberService($team);
        $model = new $this->base_model;

        $newMember = create(User::class);

        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $newMember->id;
        $teamMember = $this->newItem($attributes);
        $this->assertEquals(1, $model->all()->count());
        $oldName = $teamMember->{$this->itemColumn};

        $service->delete($teamMember);

        $this->assertEquals(0, $model->all()->count());
        $this->assertDatabaseMissing($model->getTable(), [
            $this->itemColumn => $oldName
        ]);
    }

    /** @test */
    public function it_cannot_create_new_team_member_if_already_exists()
    {
        $this->signIn();
        $team = create(Team::class, ['owner_id' => $this->user->id]);
        $service = new TeamMemberService($team);
        $model = new $this->base_model;

        $newMember = create(User::class);
        $attributes = $this->itemAttributes;
        $attributes['team_id'] = $team->id;
        $attributes['user_id'] = $newMember->id;
        $attributes['role_name'] = 'author';
        $teamMember = $this->newItem($attributes);
        $this->assertEquals(1, $model->all()->count());

        $attributes['email'] = $newMember->email;
        $teamMember = $service->create($attributes);
        $this->assertNotTrue($teamMember);
        $this->assertEquals(1, $model->all()->count());
        $this->assertNotEquals(2, $model->all()->count());
    }

    /** @test */
    public function it_cannot_update_or_delete_owner()
    {
        $this->signIn();
        $team = create(Team::class, ['owner_id' => $this->user->id]);
        $service = new TeamMemberService($team);

        $attributes = $this->itemAttributes;
        $attributes['team_id'] = $team->id;
        $attributes['user_id'] = $this->user->id;
        $attributes['role_name'] = 'admin';
        $teamMember = $this->newItem($attributes);

        $isUpdated = $service->update($teamMember, $attributes);
        $this->assertNotTrue($isUpdated);

        $isDeleted = $service->delete($teamMember);
        $this->assertNotTrue($isDeleted);
    }
}
