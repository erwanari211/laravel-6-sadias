<?php

namespace Modules\ExampleBlog\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class ExampleBlogServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(module_path('ExampleBlog', 'Database/Migrations'));
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path('ExampleBlog', 'Config/config.php') => config_path('exampleblog.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('ExampleBlog', 'Config/config.php'), 'exampleblog'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/exampleblog');

        $sourcePath = module_path('ExampleBlog', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/exampleblog';
        }, \Config::get('view.paths')), [$sourcePath]), 'exampleblog');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/exampleblog');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'exampleblog');
        } else {
            $this->loadTranslationsFrom(module_path('ExampleBlog', 'Resources/lang'), 'exampleblog');
        }
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (! app()->environment('production') && $this->app->runningInConsole()) {
            app(Factory::class)->load(module_path('ExampleBlog', 'Database/factories'));
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
