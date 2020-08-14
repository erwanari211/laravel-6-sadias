<?php

namespace Modules\ExampleBlog\Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\ExampleBlog\Models\Post;
use Modules\ExampleBlog\Models\Team;
use Modules\ExampleBlog\Models\TeamMember;

class ExampleBlogTeamPostsTableSeeder extends Seeder
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

        $teams = Team::get();
        foreach ($teams as $team) {
            $members = $team->teamMembers;
            $tags = $team->tags;

            for ($i=0; $i < 15; $i++) {
                $member = $faker->randomElement($members);
                $post = create(Post::class, [
                    'author_id' => $member->user_id,
                    'postable_id' => $team->id,
                    'postable_type' => get_class($team),
                    'unique_code' => Post::createUniqueCode(),
                    'status' => 'published',
                ]);

                $postTag = $faker->randomElements($tags);
                $postTag = collect($postTag)->pluck('id');
                $post->tags()->sync($postTag);
            }
        }
    }
}
