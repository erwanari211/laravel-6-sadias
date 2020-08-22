<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\ExampleBlog\Models\Post;

$factory->define(Post::class, function (Faker $faker) {
    return [
        
        'author_id' => $faker->numberBetween(1000, 9999),
        'unique_code' => $faker->sentence,
        'title' => $faker->sentence,
        'slug' => $faker->sentence,
        'content' => $faker->paragraph,
        'status' => $faker->sentence,

    ];
});
