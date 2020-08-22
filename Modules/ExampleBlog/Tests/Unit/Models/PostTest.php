<?php

namespace Modules\ExampleBlog\tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\ExampleBlog\Models\Post;
use App\User;

class PostTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public $itemColumn;
    public $itemUserColumn;
    public $itemAttributes;

    public function setUp(): void
    {
        parent::setUp();

        $user = create(User::class);
        $attributes = [
            'postable_id' => $user->id,
            'postable_type' => get_class($user),
        ];

        $this->itemUserColumn = 'author_id';
        $this->itemColumn = 'name';
        $this->itemAttributes = $attributes;
    }

    /** @test */
    public function a_post_belongs_to_a_author()
    {
        $author = create('App\User');
        $attributes = $this->itemAttributes;
        $attributes['author_id'] = $author->id;
        $post = create(Post::class, $attributes);
        $this->assertInstanceOf('App\User', $post->author);
    }



}
