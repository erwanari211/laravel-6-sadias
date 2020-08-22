<?php

namespace Modules\ExampleBlog\tests\Unit\Services;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Modules\ExampleBlog\Models\Tag;
use Modules\ExampleBlog\Services\TagService;
use Modules\ExampleBlog\Models\Team;
use Modules\ExampleBlog\Models\TeamMember;

class TagServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public $itemColumn;
    public $itemUserColumn;
    public $itemAttributes;

    public function setUp(): void
    {
        parent::setUp();

        $this->setBaseRoute('example-blog.tags');
        $this->setBaseModel(Tag::class);

        $attributes = [];

        $this->itemUserColumn = 'owner_id';
        $this->itemColumn = 'name';
        $this->itemAttributes = $attributes;
    }


    /** @test */
    public function it_can_fetch_users_tags()
    {
        $service = new TagService;
        $this->signIn();
        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $tag = $this->newItem($attributes);

        $tags = $service->getData();

        $tagsArray = $tags->jsonSerialize();
        // $tag->load('owner');
        $tagArray = $tag->toArray();
        // $this->assertContains($tagArray, $tagsArray);
        // $this->assertArrayHasKey('owner', $tagsArray[0]);

        $this->assertCount(1, $tagsArray);
        $this->assertEquals($tagsArray[0][$this->itemUserColumn], $tagArray[$this->itemUserColumn]);
        $this->assertEquals($tagsArray[0][$this->itemUserColumn], $this->user->id);
        $this->assertEquals($tagsArray[0][$this->itemColumn], $tagArray[$this->itemColumn]);
    }

    /** @test */
    public function it_can_fetch_team_tags()
    {
        $service = new TagService;
        $this->signIn();

        $team = create(Team::class, ['owner_id' => $this->user]);
        $teamMember = create(TeamMember::class, [
            'user_id' => $this->user->id,
            'team_id' => $team->id,
            'role_name' => 'admin',
        ]);

        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $attributes['ownerable_id'] = $team->id;
        $attributes['ownerable_type'] = get_class($team);
        $tag = $this->newItem($attributes);

        $tags = $service->getTeamTags($team);

        $tagsArray = $tags->jsonSerialize();
        // $tag->load('owner');
        $tagArray = $tag->toArray();
        // $this->assertContains($tagArray, $tagsArray);
        // $this->assertArrayHasKey('owner', $tagsArray[0]);

        $this->assertCount(1, $tagsArray);
        $this->assertEquals($tagsArray[0][$this->itemUserColumn], $tagArray[$this->itemUserColumn]);
        $this->assertEquals($tagsArray[0][$this->itemUserColumn], $this->user->id);
        $this->assertEquals($tagsArray[0][$this->itemColumn], $tagArray[$this->itemColumn]);
    }

    /** @test */
    public function it_cannot_fetch_others_tags()
    {
        $service = new TagService;
        $this->signIn();
        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $tag = $this->newItem($attributes);
        // $tag->load('owner');

        $attributes = $this->itemAttributes;
        $othertag = $this->newItem($attributes);

        $tags = $service->getData();

        $tagsArray = $tags->jsonSerialize();
        $tagArray = $tag->toArray();
        $othertagArray = $othertag->toArray();

        // $this->assertContains($tagArray, $tagsArray);
        $this->assertNotContains($othertagArray, $tagsArray);

        $this->assertCount(1, $tagsArray);
        $this->assertNotCount(2, $tagsArray);
    }

    /** @test */
    public function it_can_fetch_a_users_tag()
    {
        $service = new TagService;
        $this->signIn();
        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $tag = $this->newItem($attributes);
        // $tag->load('owner');

        $item = $service->getItem($tag->id);

        $tagArray = $tag->toArray();
        $this->assertEquals($item[$this->itemUserColumn], $tagArray[$this->itemUserColumn]);
        $this->assertEquals($item[$this->itemUserColumn], $this->user->id);
        $this->assertEquals($item[$this->itemColumn], $tagArray[$this->itemColumn]);
    }

    /** @test */
    public function it_can_create_tag()
    {
        $service = new TagService;
        $this->signIn();

        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $newName = $this->faker->sentence;
        $attributes[$this->itemColumn] = $newName;

        $data = make($this->base_model, $attributes);

        $tag = $service->create($data->toArray());

        $this->assertEquals($tag[$this->itemUserColumn], $this->user->id);
        $this->assertEquals($tag[$this->itemColumn], $newName);
        $model = new $this->base_model;
        $this->assertDatabaseHas($model->getTable(), [
            $this->itemUserColumn => $this->user->id,
            $this->itemColumn => $newName
        ]);
    }

    /** @test */
    public function it_can_update_tag()
    {
        $service = new TagService;
        $this->signIn();

        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $tag = $this->newItem($attributes);

        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $newName = $this->faker->sentence;
        $attributes[$this->itemColumn] = $newName;

        $data = make($this->base_model, $attributes);
        $oldName = $tag->name;

        $tag = $service->update($tag, $data->toArray());

        $this->assertNotEquals($tag[$this->itemColumn], $oldName);
        $this->assertEquals($tag[$this->itemColumn], $newName);
        $model = new $this->base_model;
        $this->assertDatabaseHas($model->getTable(), [
            $this->itemColumn => $newName
        ]);
        $this->assertDatabaseMissing($model->getTable(), [
            $this->itemColumn => $oldName
        ]);
    }

    /** @test */
    public function it_can_delete_tag()
    {
        $model = new $this->base_model;
        $service = new TagService;
        $this->signIn();

        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $tag = $this->newItem($attributes);
        $this->assertEquals(1, $model->all()->count());
        $oldName = $tag->{$this->itemColumn};

        $service->delete($tag);

        $this->assertEquals(0, $model->all()->count());
        $this->assertDatabaseMissing($model->getTable(), [
            $this->itemColumn => $oldName
        ]);
    }
}
