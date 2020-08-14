<?php

namespace Modules\ExampleBlog\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\ExampleBlog\Models\Tag;
use Modules\ExampleBlog\Models\Team;

class ExampleBlogTeamTagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $teams = Team::get();
        foreach ($teams as $team) {
            create(Tag::class, [
                'owner_id' => $team->owner_id,
                'ownerable_id' => $team->id,
                'ownerable_type' => get_class($team),
            ], 5);
        }
    }
}
