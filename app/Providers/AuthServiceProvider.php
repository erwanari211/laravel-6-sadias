<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        'Modules\ExampleBlog\Entities\ExampleBlogChannel' => 'Modules\ExampleBlog\Policies\ExampleBlogChannelPolicy',
        'Modules\ExampleBlog\Models\Post' => 'Modules\ExampleBlog\Policies\PostPolicy',
        'Modules\ExampleBlog\Models\Comment' => 'Modules\ExampleBlog\Policies\CommentPolicy',
        'Modules\ExampleBlog\Models\Team' => 'Modules\ExampleBlog\Policies\TeamPolicy',
        'Modules\ExampleBlog\Models\TeamMember' => 'Modules\ExampleBlog\Policies\TeamMemberPolicy',
        'Modules\ExampleBlog\Models\Tag' => 'Modules\ExampleBlog\Policies\TagPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
