<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\ExamplePermission\Models\Role;

$factory->define(Role::class, function (Faker $faker) {
    return [

        'name' => $faker->sentence,
        'guard_name' => 'web',

    ];
});
