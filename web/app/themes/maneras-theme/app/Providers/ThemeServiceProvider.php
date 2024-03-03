<?php

namespace App\Providers;

use Roots\Acorn\Sage\SageServiceProvider;

class ThemeServiceProvider extends SageServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        parent::register();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        
        add_action('after_setup_theme', function () {
            register_nav_menus([
                'primary_navigation' => __('Primary Navigation', 'sage')
            ]);
        });
    }
}
