<?php

namespace Modules\ExamplePermission\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\ExamplePermission\Models\Permission;
use Faker\Factory as Faker;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $faker = Faker::create();

        factory(Permission::class, 10)->create();
    }
}
