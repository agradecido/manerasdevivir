<?php

namespace App\Providers;

use App\Constants;
use Roots\Acorn\Sage\SageServiceProvider;
use App\View\Composers\HomeComposer;

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
        setlocale(LC_TIME, Constants::LOCALE);

        // Registro del HomeComposer
        \Roots\view()->composer(
            'home',
            HomeComposer::class
        );

        parent::boot();
    }
}
