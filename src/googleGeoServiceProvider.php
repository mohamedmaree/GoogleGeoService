<?php

namespace maree\googleGeoServices;

use Illuminate\Support\ServiceProvider;

class googleGeoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->publishes([
            __DIR__.'/config/google-geo-services.php' => config_path('google-geo-services.php'),
        ],'google-geo-services');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/google-geo-services.php', 'google-geo-services'
        );
    }
}
