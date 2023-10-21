<?php

namespace FME\ShipStation;

use Illuminate\Support\ServiceProvider;
use FME\ShipStation\ShipStationRepository;

class ShipStationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/shipstation.php' => config_path('shipstation.php'),
            ], 'config');
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/shipstation.php', 'shipstation');

        $this->app->singleton('ShipStationRepository', function () {
            return new ShipStationRepository();
        });
    }
}
