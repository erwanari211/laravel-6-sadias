<?php

namespace Modules\ExampleBlog\tests\Unit\Services;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Modules\ExampleBlog\Models\Post;
use Modules\ExampleBlog\Models\Team;
use Modules\ExampleBlog\Services\PostService;

class PostServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public $itemColumn;
    public $itemUserColumn;
    public $itemAttributes;

    public function setUp(): void
    {
        parent::setUp();

        $this->setBaseRoute('example-blog.posts');
        $this->setBaseModel(Post::class);

        $user = create(User::class);
        $attributes = [
            'postable_id' => $user->id,
            'postable_type' => get_class($user),
        ];

        $this->itemUserColumn = 'author_id';
        $this->itemColumn = 'title';
        $this->itemAttributes = $attributes;
    }


    /** @test */
    public function it_can_fetch_users_posts()
    {
        $service = new PostService;
        $this->signIn();
        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $post = $this->newItem($attributes);

        $posts = $service->getData();

        $postsArray = $posts->jsonSerialize();
        // $post->load('owner');
        $postArray = $post->toArray();
        // $this->assertContains($postArray, $postsArray);
        // $this->assertArrayHasKey('owner', $postsArray[0]);

        $this->assertCount(1, $postsArray);
        $this->assertEquals($postsArray[0][$this->itemUserColumn], $postArray[$this->itemUserColumn]);
        $this->assertEquals($postsArray[0][$this->itemUserColumn], $this->user->id);
        $this->assertEquals($postsArray[0][$this->itemColumn], $postArray[$this->itemColumn]);
    }

    /** @test */
    public function it_can_fetch_team_posts()
    {
        $service = new PostService;
        $this->signIn();
        $team = create(Team::class, ['owner_id' => $this->user->id]);
        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $post = $this->newItem($attributes);

        $attributes['postable_id'] = $team->id;
        $attributes['postable_type'] = get_class($team);
        $teamPost = $this->newItem($attributes);

        $posts = $service->getTeamPosts($team);

        $postsArray = $posts->jsonSerialize();
        // $post->load('owner');
        $postArray = $teamPost->toArray();
        // $this->assertContains($postArray, $postsArray);
        // $this->assertArrayHasKey('owner', $postsArray[0]);

        $model = new $this->base_model;
        $this->assertEquals(2, $model->all()->count());

        $this->assertCount(1, $postsArray);
        $this->assertEquals($postsArray[0][$this->itemUserColumn], $postArray[$this->itemUserColumn]);
        $this->assertEquals($postsArray[0][$this->itemUserColumn], $this->user->id);
        $this->assertEquals($postsArray[0][$this->itemColumn], $postArray[$this->itemColumn]);
    }

    /** @test */
    public function it_cannot_fetch_others_posts()
    {
        $service = new PostService;
        $this->signIn();
        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $post = $this->newItem($attributes);
        // $post->load('owner');

        $attributes = $this->itemAttributes;
        $otherpost = $this->newItem($attributes);

        $posts = $service->getData();

        $postsArray = $posts->jsonSerialize();
        $channelArray = $post->toArray();
        $otherpostArray = $otherpost->toArray();

        // $this->assertContains($channelArray, $postsArray);
        $this->assertNotContains($otherpostArray, $postsArray);

        $this->assertCount(1, $postsArray);
        $this->assertNotCount(2, $postsArray);
    }

    /** @test */
    public function it_can_fetch_a_users_post()
    {
        $service = new PostService;
        $this->signIn();
        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $post = $this->newItem($attributes);
        // $post->load('owner');

        $item = $service->getItem($post->id);

        $postArray = $post->toArray();
        $this->assertEquals($item[$this->itemUserColumn], $postArray[$this->itemUserColumn]);
        $this->assertEquals($item[$this->itemUserColumn], $this->user->id);
        $this->assertEquals($item[$this->itemColumn], $postArray[$this->itemColumn]);
    }

    /** @test */
    public function it_can_create_post()
    {
        $service = new PostService;
        $this->signIn();

        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $newName = $this->faker->sentence;
        $attributes[$this->itemColumn] = $newName;

        $data = make($this->base_model, $attributes);

        $post = $service->create($data->toArray());

        $this->assertEquals($post[$this->itemUserColumn], $this->user->id);
        $this->assertEquals($post[$this->itemColumn], $newName);
        $model = new $this->base_model;
        $this->assertDatabaseHas($model->getTable(), [
            $this->itemUserColumn => $this->user->id,
            $this->itemColumn => $newName
        ]);
    }

    /** @test */
    public function it_can_update_post()
    {
        $service = new PostService;
        $this->signIn();

        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $post = $this->newItem($attributes);

        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $newName = $this->faker->sentence;
        $attributes[$this->itemColumn] = $newName;

        $data = make($this->base_model, $attributes);
        $oldName = $post->name;

        $post = $service->update($post, $data->toArray());

        $this->assertNotEquals($post[$this->itemColumn], $oldName);
        $this->assertEquals($post[$this->itemColumn], $newName);
        $model = new $this->base_model;
        $this->assertDatabaseHas($model->getTable(), [
            $this->itemColumn => $newName
        ]);
        $this->assertDatabaseMissing($model->getTable(), [
            $this->itemColumn => $oldName
        ]);
    }

    /** @test */
    public function it_can_delete_post()
    {
        $model = new $this->base_model;
        $service = new PostService;
        $this->signIn();

        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $post = $this->newItem($attributes);
        $this->assertEquals(1, $model->all()->count());
        $oldName = $post->{$this->itemColumn};

        $service->delete($post);

        $this->assertEquals(0, $model->all()->count());
        $this->assertDatabaseMissing($model->getTable(), [
            $this->itemColumn => $oldName
        ]);
    }
}
