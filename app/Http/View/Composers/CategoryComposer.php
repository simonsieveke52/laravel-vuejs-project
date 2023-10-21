<?php

namespace App\Http\View\Composers;

use App\Brand;
use App\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;

class CategoryComposer
{
    /**
     * @var Collection
     */
    protected $categories;

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

        if (! $this->categories) {
            $this->categories = Category::onNavbar()->withDepth()->get()->toTree();
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
            'categories' => $this->categories,
            'brands' => $this->brands
        ]);
    }
}
