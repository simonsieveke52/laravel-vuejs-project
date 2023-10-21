<?php

namespace App\Providers;

use App\Product;
use App\Category;
use App\Observers\ReviewObserver;
use App\Observers\ProductObserver;
use App\Observers\CategoryObserver;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Locate\LocateApiClient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Basic app configuration
        Blade::withoutComponentTags();
        Schema::defaultStringLength(191);

        // Models observer
        Category::observe(CategoryObserver::class);
    }
}
