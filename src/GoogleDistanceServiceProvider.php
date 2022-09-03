<?php

namespace Dwikipeddos\LaravelGoogleDistance;

use Illuminate\Support\ServiceProvider;

class GoogleDistanceServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/google-distance.php' => config_path('google-distance.php'),
        ]);
    }
}
