<?php

namespace Modules\ExampleBlog\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\ExampleBlog\Models\TeamMember;
use Modules\ExampleBlog\Models\Team;
use App\User;

class ExampleBlogTeamMembersTableSeeder extends Seeder
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
            create(TeamMember::class, [
                'team_id' => $team->id,
                'user_id' => $team->owner_id,
                'role_name' => 'admin',
                'is_active' => true,
            ]);

            for ($i=0; $i < 3; $i++) {
                $user = create(User::class, [
                    'email' => 'team_member_'.$team->id.'_'.$i.'@app.com',
                    'password' => bcrypt(12345678),
                ]);
                create(TeamMember::class, [
                    'team_id' => $team->id,
                    'user_id' => $user->id,
                    'role_name' => 'author',
                ]);
            }
        }
    }
}
