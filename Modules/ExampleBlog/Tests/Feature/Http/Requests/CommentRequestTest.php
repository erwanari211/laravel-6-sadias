<?php

namespace Modules\ExampleBlog\tests\Feature\Http\Requests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Modules\ExampleBlog\Models\Comment;

class CommentRequestTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public $itemColumn;
    public $itemUserColumn;
    public $itemAttributes;

    public function setUp(): void
    {
        parent::setUp();

        $this->setBaseRoute('example-blog.comments');
        $this->setBaseModel(Comment::class);

        $attributes = [
            'status' => 'published',
        ];

        $this->itemUserColumn = 'author_id';
        $this->itemColumn = 'content';
        $this->itemAttributes = $attributes;
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
    public function it_validate_store_comment_request($field, $formFields, $shouldPass = false)
    {
        $this->signIn();
        $this->beforeValidateStoreRequest();

        $response = $this->createItem($this->getItemFields($formFields));

        if (!$shouldPass) {
            $response->assertSessionHasErrors([$field]);
        } else {
            $response->assertSessionHasNoErrors([$field]);
        }
    }

    public function beforeValidateStoreRequest()
    {

    }

    public function storeItemDataProvider()
    {
        return [

            'request_should_fail_when_no_author_id_is_provided' => [
                'field' => 'author_id',
                'data' => ['author_id' => ''],
                'passed' => false,
            ],
            'request_should_success_when_author_id_is_provided' => [
                'field' => 'author_id',
                'data' => ['author_id' => '99'],
                'passed' => true,
            ],

            'request_should_fail_when_author_id_value_is_not_integer' => [
                'field' => 'author_id',
                'data' => ['author_id' => 'not-integer'],
                'passed' => false,
            ],
            'request_should_success_when_author_id_value_is_not_integer' => [
                'field' => 'author_id',
                'data' => ['author_id' => '99'],
                'passed' => true,
            ],

            'request_should_fail_when_no_post_id_is_provided' => [
                'field' => 'post_id',
                'data' => ['post_id' => ''],
                'passed' => false,
            ],
            'request_should_success_when_post_id_is_provided' => [
                'field' => 'post_id',
                'data' => ['post_id' => '99'],
                'passed' => true,
            ],

            'request_should_fail_when_post_id_value_is_not_integer' => [
                'field' => 'post_id',
                'data' => ['post_id' => 'not-integer'],
                'passed' => false,
            ],
            'request_should_success_when_post_id_value_is_not_integer' => [
                'field' => 'post_id',
                'data' => ['post_id' => '99'],
                'passed' => true,
            ],

            'request_should_success_when_parent_id_is_filled_or_not' => [
                'field' => 'parent_id',
                'data' => ['parent_id' => ''],
                'passed' => true,
            ],

            'request_should_fail_when_parent_id_value_is_not_integer' => [
                'field' => 'parent_id',
                'data' => ['parent_id' => 'not-integer'],
                'passed' => false,
            ],
            'request_should_success_when_parent_id_value_is_not_integer' => [
                'field' => 'parent_id',
                'data' => ['parent_id' => '99'],
                'passed' => true,
            ],

            'request_should_fail_when_no_content_is_provided' => [
                'field' => 'content',
                'data' => ['content' => ''],
                'passed' => false,
            ],
            'request_should_success_when_content_is_provided' => [
                'field' => 'content',
                'data' => ['content' => 'content'],
                'passed' => true,
            ],

            'request_should_fail_when_no_is_approved_is_provided' => [
                'field' => 'is_approved',
                'data' => ['is_approved' => ''],
                'passed' => false,
            ],
            'request_should_success_when_is_approved_is_provided' => [
                'field' => 'is_approved',
                'data' => ['is_approved' => '1'],
                'passed' => true,
            ],

            'request_should_fail_when_no_status_is_provided' => [
                'field' => 'status',
                'data' => ['status' => ''],
                'passed' => false,
            ],
            'request_should_success_when_status_is_provided' => [
                'field' => 'status',
                'data' => ['status' => 'published'],
                'passed' => true,
            ],


        ];
    }

    /**
     * @test
     * @dataProvider updateItemDataProvider
     */
    public function it_validate_update_comment_request($field, $formFields, $shouldPass = false)
    {
        $this->signIn();
        $this->beforeValidateUpdateRequest();

        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $comment = $this->newItem($attributes);

        $response = $this->updateItem($comment->id, $this->getItemFields($formFields));

        if (!$shouldPass) {
            $response->assertSessionHasErrors([$field]);
        } else {
            $response->assertSessionHasNoErrors([$field]);
        }
    }

    public function beforeValidateUpdateRequest()
    {

    }

    public function updateItemDataProvider()
    {
        return [

            'request_should_fail_when_no_author_id_is_provided' => [
                'field' => 'author_id',
                'data' => ['author_id' => ''],
                'passed' => false,
            ],
            'request_should_success_when_author_id_is_provided' => [
                'field' => 'author_id',
                'data' => ['author_id' => '99'],
                'passed' => true,
            ],

            'request_should_fail_when_author_id_value_is_not_integer' => [
                'field' => 'author_id',
                'data' => ['author_id' => 'not-integer'],
                'passed' => false,
            ],
            'request_should_success_when_author_id_value_is_not_integer' => [
                'field' => 'author_id',
                'data' => ['author_id' => '99'],
                'passed' => true,
            ],

            'request_should_fail_when_no_post_id_is_provided' => [
                'field' => 'post_id',
                'data' => ['post_id' => ''],
                'passed' => false,
            ],
            'request_should_success_when_post_id_is_provided' => [
                'field' => 'post_id',
                'data' => ['post_id' => '99'],
                'passed' => true,
            ],

            'request_should_fail_when_post_id_value_is_not_integer' => [
                'field' => 'post_id',
                'data' => ['post_id' => 'not-integer'],
                'passed' => false,
            ],
            'request_should_success_when_post_id_value_is_not_integer' => [
                'field' => 'post_id',
                'data' => ['post_id' => '99'],
                'passed' => true,
            ],

            'request_should_success_when_parent_id_is_filled_or_not' => [
                'field' => 'parent_id',
                'data' => ['parent_id' => ''],
                'passed' => true,
            ],

            'request_should_fail_when_parent_id_value_is_not_integer' => [
                'field' => 'parent_id',
                'data' => ['parent_id' => 'not-integer'],
                'passed' => false,
            ],
            'request_should_success_when_parent_id_value_is_not_integer' => [
                'field' => 'parent_id',
                'data' => ['parent_id' => '99'],
                'passed' => true,
            ],

            'request_should_fail_when_no_content_is_provided' => [
                'field' => 'content',
                'data' => ['content' => ''],
                'passed' => false,
            ],
            'request_should_success_when_content_is_provided' => [
                'field' => 'content',
                'data' => ['content' => 'content'],
                'passed' => true,
            ],

            'request_should_fail_when_no_is_approved_is_provided' => [
                'field' => 'is_approved',
                'data' => ['is_approved' => ''],
                'passed' => false,
            ],
            'request_should_success_when_is_approved_is_provided' => [
                'field' => 'is_approved',
                'data' => ['is_approved' => '1'],
                'passed' => true,
            ],

            'request_should_fail_when_no_status_is_provided' => [
                'field' => 'status',
                'data' => ['status' => ''],
                'passed' => false,
            ],
            'request_should_success_when_status_is_provided' => [
                'field' => 'status',
                'data' => ['status' => 'published'],
                'passed' => true,
            ],


        ];
    }

    /** @test */
    public function authenticated_user_can_create_comment()
    {
        $this->signIn();

        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $this->createItem($attributes);

        $model = new $this->base_model;
        $this->assertDatabaseHas($model->getTable(), [$this->itemUserColumn => $this->user->id]);
    }

    /** @test */
    public function guest_cannot_create_comment()
    {
        $attributes = $this->itemAttributes;
        $response = $this->createItem($attributes);

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    /** @test */
    public function authenticated_user_can_update_comment()
    {
        $this->signIn();

        $attributes = $this->itemAttributes;
        $attributes[$this->itemUserColumn] = $this->user->id;
        $comment = $this->newItem($attributes);
        $oldName = $comment->{$this->itemColumn};

        $newName = $this->faker->sentence;
        $attributes['content'] = $newName;
        $this->updateItem($comment->id, $attributes);

        $comment->refresh();
        $model = new $this->base_model;
        $this->assertDatabaseHas($model->getTable(), [
            $this->itemUserColumn => $this->user->id,
            $this->itemColumn => $comment->{$this->itemColumn},
        ]);
        $this->assertDatabaseMissing($model->getTable(), [
            $this->itemUserColumn => $this->user->id,
            $this->itemColumn => $oldName,
        ]);
    }

    /** @test */
    public function authenticated_user_cannot_update_others_comment()
    {
        $this->signIn();
        $attributes = $this->itemAttributes;
        $other = $this->newItem($this->itemAttributes);

        $response = $this->updateItem($other->id);

        $response->assertStatus(403);
    }
}
