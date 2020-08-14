<?php

namespace Modules\ExampleBlog\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\ExampleBlog\Models\Team;
use App\User;

class ExampleBlogTeamsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        for ($i=1; $i <= 3; $i++) {
            $user = create(User::class, [
                'email' => 'team_admin_'.$i.'@app.com',
                'password' => bcrypt(12345678),
            ]);
            create(Team::class, ['owner_id' => $user->id]);
        }
    }
}
