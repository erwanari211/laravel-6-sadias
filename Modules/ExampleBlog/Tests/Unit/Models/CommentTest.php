<?php

namespace Modules\ExampleBlog\tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\ExampleBlog\Models\Comment;
use App\User;

class CommentTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public $itemColumn;
    public $itemUserColumn;
    public $itemAttributes;

    public function setUp(): void
    {
        parent::setUp();

        $attributes = [];

        $this->itemUserColumn = 'author_id';
        $this->itemColumn = 'name';
        $this->itemAttributes = $attributes;
    }

    /** @test */
    public function a_comment_belongs_to_a_author()
    {
        $author = create('App\User');
        $attributes = $this->itemAttributes;
        $attributes['author_id'] = $author->id;
        $comment = create(Comment::class, $attributes);
        $this->assertInstanceOf('App\User', $comment->author);
    }

    /** @test */
    public function a_comment_belongs_to_a_post()
    {
        $user = create(User::class);
        $attributes = [
            'postable_id' => $user->id,
            'postable_type' => get_class($user),
        ];
        $post = create('Modules\ExampleBlog\Models\Post', $attributes);
        $attributes = $this->itemAttributes;
        $attributes['post_id'] = $post->id;
        $comment = create(Comment::class, $attributes);
        $this->assertInstanceOf('Modules\ExampleBlog\Models\Post', $comment->post);
    }



}
