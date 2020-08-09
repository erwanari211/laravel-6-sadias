<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\ExampleBlog\Models\TeamMember;

$factory->define(TeamMember::class, function (Faker $faker) {
    return [
        
        'user_id' => $faker->numberBetween(1000, 9999),
        'team_id' => $faker->numberBetween(1000, 9999),
        'role_name' => $faker->sentence,
        'is_active' => $faker->boolean(80),

    ];
});
