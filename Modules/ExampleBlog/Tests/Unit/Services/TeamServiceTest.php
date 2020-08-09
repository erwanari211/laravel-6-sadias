<?php

namespace Modules\ExampleBlog\tests\Unit\Services;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Modules\ExampleBlog\Models\Team;
use Modules\ExampleBlog\Services\TeamService;

class TeamServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public $itemColumn;
    public $itemUserColumn;
    public $itemAttributes;

    public function setUp(): void
    {
        parent::setUp();

        $this->setBaseRoute('example-blog.teams');
        $this->setBaseModel(Team::class);

        $attributes = [];

        $this->itemUserColumn = 'owner_id';
        $this->itemColumn = 'name';
        $this->itemAttributes = $attributes;
    }


    /** @test */
    public function it_can_fetch_users_teams()
    {
        $service = new TeamService;
        $this->signIn();
        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $team = $this->newItem($attributes);

        $teams = $service->getData();

        $teamsArray = $teams->jsonSerialize();
        // $team->load('owner');
        $teamArray = $team->toArray();
        // $this->assertContains($teamArray, $teamsArray);
        // $this->assertArrayHasKey('owner', $teamsArray[0]);

        $this->assertCount(1, $teamsArray);
        $this->assertEquals($teamsArray[0][$this->itemUserColumn], $teamArray[$this->itemUserColumn]);
        $this->assertEquals($teamsArray[0][$this->itemUserColumn], $this->user->id);
        $this->assertEquals($teamsArray[0][$this->itemColumn], $teamArray[$this->itemColumn]);
    }

    /** @test */
    public function it_cannot_fetch_others_teams()
    {
        $service = new TeamService;
        $this->signIn();
        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $team = $this->newItem($attributes);
        // $team->load('owner');

        $attributes = $this->itemAttributes;
        $otherteam = $this->newItem($attributes);

        $teams = $service->getData();

        $teamsArray = $teams->jsonSerialize();
        $teamArray = $team->toArray();
        $otherteamArray = $otherteam->toArray();

        // $this->assertContains($teamArray, $teamsArray);
        $this->assertNotContains($otherteamArray, $teamsArray);

        $this->assertCount(1, $teamsArray);
        $this->assertNotCount(2, $teamsArray);
    }

    /** @test */
    public function it_can_fetch_a_users_team()
    {
        $service = new TeamService;
        $this->signIn();
        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $team = $this->newItem($attributes);
        // $team->load('owner');

        $item = $service->getItem($team->id);

        $teamArray = $team->toArray();
        $this->assertEquals($item[$this->itemUserColumn], $teamArray[$this->itemUserColumn]);
        $this->assertEquals($item[$this->itemUserColumn], $this->user->id);
        $this->assertEquals($item[$this->itemColumn], $teamArray[$this->itemColumn]);
    }

    /** @test */
    public function it_can_create_team()
    {
        $service = new TeamService;
        $this->signIn();

        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $newName = $this->faker->sentence;
        $attributes[$this->itemColumn] = $newName;

        $data = make($this->base_model, $attributes);

        $team = $service->create($data->toArray());

        $this->assertEquals($team[$this->itemUserColumn], $this->user->id);
        $this->assertEquals($team[$this->itemColumn], $newName);
        $model = new $this->base_model;
        $this->assertDatabaseHas($model->getTable(), [
            $this->itemUserColumn => $this->user->id,
            $this->itemColumn => $newName
        ]);
    }

    /** @test */
    public function it_can_update_team()
    {
        $service = new TeamService;
        $this->signIn();

        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $team = $this->newItem($attributes);

        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $newName = $this->faker->sentence;
        $attributes[$this->itemColumn] = $newName;

        $data = make($this->base_model, $attributes);
        $oldName = $team->name;

        $team = $service->update($team, $data->toArray());

        $this->assertNotEquals($team[$this->itemColumn], $oldName);
        $this->assertEquals($team[$this->itemColumn], $newName);
        $model = new $this->base_model;
        $this->assertDatabaseHas($model->getTable(), [
            $this->itemColumn => $newName
        ]);
        $this->assertDatabaseMissing($model->getTable(), [
            $this->itemColumn => $oldName
        ]);
    }

    /** @test */
    public function it_can_delete_team()
    {
        $model = new $this->base_model;
        $service = new TeamService;
        $this->signIn();

        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $team = $this->newItem($attributes);
        $this->assertEquals(1, $model->all()->count());
        $oldName = $team->{$this->itemColumn};

        $service->delete($team);

        $this->assertEquals(0, $model->all()->count());
        $this->assertDatabaseMissing($model->getTable(), [
            $this->itemColumn => $oldName
        ]);
    }
}
