<?php

namespace FME\Fedex;

use FME\Fedex\FedexRepository;
use Illuminate\Support\ServiceProvider;

class FedexServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/fedex.php' => config_path('fedex.php'),
            ], 'config');
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/fedex.php', 'fedex');

        $this->app->singleton('FedexRepository', function () {
            return new FedexRepository();
        });
    }
}
