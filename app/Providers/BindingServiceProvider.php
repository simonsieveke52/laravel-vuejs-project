<?php

namespace App\Providers;

use App\Repositories\CartRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\DiscountRepository;
use App\Repositories\Contracts\CartRepositoryContract;

class BindingServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CartRepositoryContract::class, CartRepository::class);

        $this->app->singleton(CartRepository::class, function () {
            return new CartRepository();
        });
    }
}
