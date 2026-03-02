<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
            $this->app['request']->server->set('HTTPS', 'on');
        }

        \Illuminate\Database\Eloquent\Model::shouldBeStrict(!app()->isProduction());

        // Share categories with all views for navigation
        try {
            // Using View Composer to avoid issues during migration/seed
            \Illuminate\Support\Facades\View::composer('*', function ($view) {
                // Cache this query if needed, for now direct
                $view->with('honeyCategories', \App\Models\Category::where('slug', '!=', 'honey')->get());
            });
        } catch (\Exception $e) {
            // Log or ignore if table doesn't exist yet
        }
    }
}
