<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\ExampleBlog\Models\Team;

$factory->define(Team::class, function (Faker $faker) {
    return [
        
        'owner_id' => $faker->numberBetween(1000, 9999),
        'name' => $faker->sentence,
        'slug' => $faker->sentence,
        'description' => $faker->paragraph,
        'is_active' => $faker->boolean(80),

    ];
});
