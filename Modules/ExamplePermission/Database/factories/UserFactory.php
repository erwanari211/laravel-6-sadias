<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\ExamplePermission\Models\User;

$factory->define(User::class, function (Faker $faker) {
    return [
        
        'name' => $faker->sentence,
        'email' => $faker->sentence,
        'password' => $faker->sentence,
        'password_confirmation' => $faker->sentence,

    ];
});
