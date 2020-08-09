<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\ExampleBlog\Models\Comment;

$factory->define(Comment::class, function (Faker $faker) {
    return [
        
        'author_id' => $faker->numberBetween(1000, 9999),
        'post_id' => $faker->numberBetween(1000, 9999),
        'parent_id' => $faker->numberBetween(1000, 9999),
        'content' => $faker->paragraph,
        'is_approved' => $faker->boolean(80),
        'status' => $faker->sentence,

    ];
});
