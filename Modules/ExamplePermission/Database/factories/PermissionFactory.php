<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\ExamplePermission\Models\Permission;

$factory->define(Permission::class, function (Faker $faker) {
    return [
        
        'name' => $faker->sentence,
        'guard_name' => $faker->sentence,

    ];
});
