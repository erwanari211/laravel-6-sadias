<?php

namespace Modules\ExampleBlog\tests\Unit\Services;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
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
        $service = new TeamMemberService;
        $this->signIn();
        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
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
        $service = new TeamMemberService;
        $this->signIn();
        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $teamMember = $this->newItem($attributes);
        // $teamMember->load('owner');

        $attributes = $this->itemAttributes;
        $otherteamMember = $this->newItem($attributes);

        $teamMembers = $service->getData();

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
        $service = new TeamMemberService;
        $this->signIn();
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
        $service = new TeamMemberService;
        $this->signIn();

        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $newName = $this->faker->sentence;
        $attributes[$this->itemColumn] = $newName;

        $data = make($this->base_model, $attributes);

        $teamMember = $service->create($data->toArray());

        $this->assertEquals($teamMember[$this->itemUserColumn], $this->user->id);
        $this->assertEquals($teamMember[$this->itemColumn], $newName);
        $model = new $this->base_model;
        $this->assertDatabaseHas($model->getTable(), [
            $this->itemUserColumn => $this->user->id,
            $this->itemColumn => $newName
        ]);
    }

    /** @test */
    public function it_can_update_teamMember()
    {
        $service = new TeamMemberService;
        $this->signIn();

        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $teamMember = $this->newItem($attributes);

        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $newName = $this->faker->sentence;
        $attributes[$this->itemColumn] = $newName;

        $data = make($this->base_model, $attributes);
        $oldName = $teamMember->name;

        $teamMember = $service->update($teamMember, $data->toArray());

        $this->assertNotEquals($teamMember[$this->itemColumn], $oldName);
        $this->assertEquals($teamMember[$this->itemColumn], $newName);
        $model = new $this->base_model;
        $this->assertDatabaseHas($model->getTable(), [
            $this->itemColumn => $newName
        ]);
        $this->assertDatabaseMissing($model->getTable(), [
            $this->itemColumn => $oldName
        ]);
    }

    /** @test */
    public function it_can_delete_teamMember()
    {
        $model = new $this->base_model;
        $service = new TeamMemberService;
        $this->signIn();

        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $teamMember = $this->newItem($attributes);
        $this->assertEquals(1, $model->all()->count());
        $oldName = $teamMember->{$this->itemColumn};

        $service->delete($teamMember);

        $this->assertEquals(0, $model->all()->count());
        $this->assertDatabaseMissing($model->getTable(), [
            $this->itemColumn => $oldName
        ]);
    }
}
