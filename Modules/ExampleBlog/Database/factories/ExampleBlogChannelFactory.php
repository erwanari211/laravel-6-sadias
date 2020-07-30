<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\ExampleBlog\Entities\ExampleBlogChannel as Channel;
use App\User;

$factory->define(Channel::class, function (Faker $faker) {
    return [
        'owner_id' => function(){
            return factory(User::class)->create()->id;
        },
        'name' => $faker->sentence,
        'slug' => $faker->slug,
        'description' => $faker->paragraph,
        'is_active' => true,
    ];
});
