<?php

namespace Modules\ExampleBlog\tests\Feature\Http\Requests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Modules\ExampleBlog\Models\Post;
use Modules\ExampleBlog\Models\Team;
use Modules\ExampleBlog\Models\TeamMember;

class TeamPostRequestTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public $itemColumn;
    public $itemUserColumn;
    public $itemAttributes;
    public $team;

    public function setUp(): void
    {
        parent::setUp();

        $this->setBaseRoute('example-blog.teams.posts');
        $this->setBaseModel(Post::class);

        $user = create(User::class);
        $team = create(Team::class, ['owner_id' => $user->id]);
        $attributes = [
            'postable_id' => $team->id,
            'postable_type' => get_class($team),
            'status' => 'draft',
        ];
        $teamMember = create(TeamMember::class, [
            'user_id' => $user->id,
            'team_id' => $team->id,
            'role_name' => 'admin',
        ]);

        $this->itemUserColumn = 'author_id';
        $this->itemColumn = 'title';
        $this->itemAttributes = $attributes;

        $this->user = $user;
        $this->team = $team;
        $this->teamMember = $teamMember;
    }

    private function getItemFields($overrides = [])
    {
        $item = make($this->base_model, $this->itemAttributes);
        $data = $item->toArray();
        $itemFields = array_merge($data, $overrides);
        return $itemFields;
    }

    /**
     * @test
     * @dataProvider storeItemDataProvider
     */
    public function it_validate_store_post_request($field, $formFields, $shouldPass = false)
    {
        $this->signIn($this->user);
        $this->beforeValidateStoreRequest();

        $response = $this->createItem($this->getItemFields($formFields), [$this->team->id]);

        if (!$shouldPass) {
            $response->assertSessionHasErrors([$field]);
        } else {
            $response->assertSessionHasNoErrors([$field]);
        }
    }

    public function beforeValidateStoreRequest()
    {
        $attributes = $this->itemAttributes;
        $attributes['slug'] = 'old-slug';
        $this->newItem($attributes);


    }

    public function storeItemDataProvider()
    {
        return [

            'request_should_fail_when_no_title_is_provided' => [
                'field' => 'title',
                'data' => ['title' => '', 'tags' => [1,2,3]],
                'passed' => false,
            ],
            'request_should_success_when_title_is_provided' => [
                'field' => 'title',
                'data' => ['title' => 'title', 'tags' => [1,2,3]],
                'passed' => true,
            ],

            'request_should_fail_when_no_slug_is_provided' => [
                'field' => 'slug',
                'data' => ['slug' => '', 'tags' => [1,2,3]],
                'passed' => false,
            ],
            'request_should_success_when_slug_is_provided' => [
                'field' => 'slug',
                'data' => ['slug' => 'slug', 'tags' => [1,2,3]],
                'passed' => true,
            ],

            'request_should_fail_when_no_content_is_provided' => [
                'field' => 'content',
                'data' => ['content' => '', 'tags' => [1,2,3]],
                'passed' => false,
            ],
            'request_should_success_when_content_is_provided' => [
                'field' => 'content',
                'data' => ['content' => 'content', 'tags' => [1,2,3]],
                'passed' => true,
            ],

            'request_should_fail_when_status_is_valid' => [
                'field' => 'status',
                'data' => ['status' => 'not-valid', 'tags' => [1,2,3]],
                'passed' => false,
            ],
            'request_should_success_when_status_is_valid' => [
                'field' => 'status',
                'data' => ['status' => 'published', 'tags' => [1,2,3]],
                'passed' => true,
            ],

            'request_should_fail_when_no_tags_is_provided' => [
                'field' => 'tags',
                'data' => [],
                'passed' => false,
            ],
            'request_should_success_when_tags_is_provided' => [
                'field' => 'tags',
                'data' => ['tags' => [1,2,3]],
                'passed' => true,
            ],


        ];
    }

    /**
     * @test
     * @dataProvider updateItemDataProvider
     */
    public function it_validate_update_post_request($field, $formFields, $shouldPass = false)
    {
        $this->signIn($this->user);
        $this->beforeValidateUpdateRequest();

        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $post = $this->newItem($attributes);

        $response = $this->updateItem([$this->team->id, $post->id], $this->getItemFields($formFields));

        if (!$shouldPass) {
            $response->assertSessionHasErrors([$field]);
        } else {
            $response->assertSessionHasNoErrors([$field]);
        }
    }

    public function beforeValidateUpdateRequest()
    {
        $attributes = $this->itemAttributes;
        $attributes['slug'] = 'old-slug';
        $this->newItem($attributes);


    }

    public function updateItemDataProvider()
    {
        return [

            'request_should_fail_when_no_title_is_provided' => [
                'field' => 'title',
                'data' => ['title' => '', 'tags' => [1,2,3]],
                'passed' => false,
            ],
            'request_should_success_when_title_is_provided' => [
                'field' => 'title',
                'data' => ['title' => 'title', 'tags' => [1,2,3]],
                'passed' => true,
            ],

            'request_should_fail_when_no_slug_is_provided' => [
                'field' => 'slug',
                'data' => ['slug' => '', 'tags' => [1,2,3]],
                'passed' => false,
            ],
            'request_should_success_when_slug_is_provided' => [
                'field' => 'slug',
                'data' => ['slug' => 'slug', 'tags' => [1,2,3]],
                'passed' => true,
            ],

            'request_should_fail_when_no_content_is_provided' => [
                'field' => 'content',
                'data' => ['content' => '', 'tags' => [1,2,3]],
                'passed' => false,
            ],
            'request_should_success_when_content_is_provided' => [
                'field' => 'content',
                'data' => ['content' => 'content', 'tags' => [1,2,3]],
                'passed' => true,
            ],

            'request_should_fail_when_status_is_valid' => [
                'field' => 'status',
                'data' => ['status' => 'not-valid', 'tags' => [1,2,3]],
                'passed' => false,
            ],
            'request_should_success_when_status_is_valid' => [
                'field' => 'status',
                'data' => ['status' => 'published', 'tags' => [1,2,3]],
                'passed' => true,
            ],

            'request_should_fail_when_no_tags_is_provided' => [
                'field' => 'tags',
                'data' => [],
                'passed' => false,
            ],
            'request_should_success_when_tags_is_provided' => [
                'field' => 'tags',
                'data' => ['tags' => [1,2,3]],
                'passed' => true,
            ],


        ];
    }

    /** @test */
    public function authenticated_user_can_create_post()
    {
        $this->signIn($this->user);

        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $attributes['tags'] = [1,2,3];
        $this->createItem($attributes, [$this->team->id]);

        $model = new $this->base_model;
        $this->assertDatabaseHas($model->getTable(), [$this->itemUserColumn => $this->user->id]);
    }

    /** @test */
    public function guest_cannot_create_post()
    {
        $attributes = $this->itemAttributes;
        $response = $this->createItem($attributes, [$this->team->id]);

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    /** @test */
    public function authenticated_user_can_update_post()
    {
        $this->signIn($this->user);

        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $post = $this->newItem($attributes);
        $oldName = $post->{$this->itemColumn};

        $attributes['tags'] = [1,2,3];
        $this->updateItem([$this->team->id, $post->id], $attributes);

        $post->refresh();
        $model = new $this->base_model;
        $this->assertDatabaseHas($model->getTable(), [
            $this->itemUserColumn => $this->user->id,
            $this->itemColumn => $post->{$this->itemColumn},
        ]);
        $this->assertDatabaseMissing($model->getTable(), [
            $this->itemUserColumn => $this->user->id,
            $this->itemColumn => $oldName,
        ]);
    }

    /** @test */
    public function authenticated_user_cannot_update_others_post()
    {
        $this->signIn();
        $attributes = $this->itemAttributes;
        $other = $this->newItem($this->itemAttributes);

        $response = $this->updateItem([$this->team->id, $other->id]);

        $response->assertStatus(403);
    }
}
