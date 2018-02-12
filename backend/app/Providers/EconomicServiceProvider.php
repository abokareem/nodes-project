<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class EconomicServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Services\Math\MathInterface',
            'App\Services\Math\BCMathService'
        );

    }
}
