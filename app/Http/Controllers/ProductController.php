<?php

namespace App\Http\Controllers;

use App\Product;
use App\Category;
use App\Rules\IsEmpty;
use App\CategoryProduct;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Gloudemans\Shoppingcart\CartItem;
use Illuminate\Support\Facades\Cookie;
use App\Repositories\ProductRepository;

class ProductController
{
    /**
     * @var ProductRepository
     */
    protected $productRepo;

    /**
     * [__construct description]
     */
    public function __construct()
    {
        $this->productRepo = new ProductRepository();
    }

    /**
     * @param  Request $request
     * @param  Product $product
     *
     * @return JsonResponse
     */
    public function index(Request $request, Product $product)
    {
        if (! $request->ajax()) {
            abort(404);
        }

        if (! $product->exists) {
            abort(404);
        }

        return $product->options();
    }

    /**
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function search(Request $request)
    {
        if (trim($request->input('keyword')) === '') {
            return redirect()->back();
        }

        $query = Product::with(['images', 'brand', 'children']);

        switch ($request->get('sortBy', 'relevance')) {
            case 'l-t-h':
                $query = $query->orderBy('price', 'asc');
                break;

            case 'h-t-l':
                $query = $query->orderBy('price', 'desc');
                break;
        }

        $query = ! ($request->has('ids') && is_array($request->input('ids')))
            ? $query->search($request->input('keyword'))
            : $query->whereIn('id', $request->input('ids'));

        $products = $query->remember(config('default-variables.cache_life_time'))->paginate(
            $request->get('perPage', 24)
        );

        try {
            $category = $products->first()->categories->first();
            $categoryChilds = $category->children->map(function ($item) {
                $item->depth += 1;
                return $item->only(['slug', 'name', 'depth']);
            });
        } catch (\Exception $exception) {
            $category = null;
        }

        $parentCategories = Category::remember(config('default-variables.cache_life_time'))
            ->ancestorsAndSelf($category);

        $parentCategoriesIds = $parentCategories->pluck('id')->all();

        if (! $request->ajax()) {
            return view('front.categories.list', [
                'parentCategoriesIds' => $parentCategoriesIds,
                'category' => $category,
            ]);
        }

        return response()->json([
            'parentCategories' => $parentCategories,
            'navItems' => $categoryChilds ?? [],
            'products' => $products,
            'category' => $category,
            'maxPrice' => floatval($query->max('price')),
            'minPrice' => floatval($query->min('price')),
        ]);
    }

    /**
     * Get the product
     *
     * @param Mixed $product
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($product)
    {
        $product = $this->productRepo->getProductWithDetails($product);

        // make this product visited
        if (! Cookie::has((string) $product->id)) {
            Cookie::queue(Cookie::make((string) $product->id, '1', 60));
            $product->increment('clicks_counter');
        }

        if (! ($product->category instanceof Category)) {
            return view('front.products.show', [
                'product' => $product,
                'parentCategories' => [],
                'relatedProducts' => collect()
            ]);
        }

        $parentCategories = Category::ancestorsAndSelf($product->category);

        $relatedProducts = CategoryProduct::whereIn('category_id', $parentCategories->pluck('id')->toArray())
            ->whereNotIn('product_id', [$product->id, $product->parent_id])
            ->whereHas('products', function ($query) {
                return $query->whereNull('parent_id');
            })
            ->with(['products.images', 'products.categories', 'products.children', 'products.brand', 'products.availability'])
            ->take(10)
            ->get()
            ->map(function ($categoryProduct) use ($product) {
                return $categoryProduct->products->whereNotIn('id', [$product->id, $product->parent_id])->first();
            });

        return view('front.products.show', [
            'product' => $product,
            'options' => $product->options() ?? [],
            'parentCategories' => $parentCategories ?? [],
            'relatedProducts' => $relatedProducts ?? collect(),
        ]);
    }
}
