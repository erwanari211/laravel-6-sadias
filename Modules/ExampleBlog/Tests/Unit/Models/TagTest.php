<?php

namespace Modules\ExampleBlog\tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\ExampleBlog\Models\Tag;
use App\User;

class TagTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public $itemColumn;
    public $itemUserColumn;
    public $itemAttributes;

    public function setUp(): void
    {
        parent::setUp();

        $attributes = [];

        $this->itemUserColumn = 'owner_id';
        $this->itemColumn = 'name';
        $this->itemAttributes = $attributes;
    }

    /** @test */
    public function a_tag_belongs_to_a_owner()
    {
        $owner = create('App\User');
        $attributes = $this->itemAttributes;
        $attributes['owner_id'] = $owner->id;
        $tag = create(Tag::class, $attributes);
        $this->assertInstanceOf('App\User', $tag->owner);
    }



}
