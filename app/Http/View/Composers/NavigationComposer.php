<?php

namespace App\Http\View\Composers;

use App\Brand;
use App\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;

class NavigationComposer
{
    /**
     * @var Collection
     */
    protected $navigationCategories;

    /**
     * @var Collection
     */
    protected $brands;

    /**
     * Create a new categories composer.
     *
     * @return void
     */
    public function __construct()
    {
        if (! $this->brands) {
            $this->brands = Brand::remember(now()->addDay())->get();
        }

        if (! $this->navigationCategories) {
            $this->navigationCategories = Category::onNavbar()->whereNull('parent_id')
                ->withDepth()
                ->get()
                ->toTree();
        }
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with([
            'categories' => $this->navigationCategories,
            'brands' => $this->brands,
        ]);
    }
}
