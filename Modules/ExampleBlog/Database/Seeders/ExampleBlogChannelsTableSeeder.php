<?php

namespace Modules\ExampleBlog\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\ExampleBlog\Entities\ExampleBlogChannel as Channel;
use App\User;

class ExampleBlogChannelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        for ($i=1; $i <= 10; $i++) {
            $user = create(User::class, [
                'email' => 'channel_owner'.$i.'@app.com',
                'password' => bcrypt(12345678),
            ]);
            create(Channel::class, ['owner_id' => $user->id]);
        }

    }
}
