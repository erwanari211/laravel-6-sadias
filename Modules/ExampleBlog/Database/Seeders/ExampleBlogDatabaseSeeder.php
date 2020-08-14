<?php

namespace Modules\ExampleBlog\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\ExampleBlog\Database\Seeders\ExampleBlogChannelsTableSeeder;
class ExampleBlogDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call("OthersTableSeeder");
        // $this->call(ExampleBlogChannelsTableSeeder::class);
        $this->call(ExampleBlogTeamsTableSeeder::class);
        $this->call(ExampleBlogTeamMembersTableSeeder::class);
        $this->call(ExampleBlogTeamTagsTableSeeder::class);
        $this->call(ExampleBlogTeamPostsTableSeeder::class);
    }
}
