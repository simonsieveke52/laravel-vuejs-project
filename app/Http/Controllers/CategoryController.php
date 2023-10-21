<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Product;
use App\Category;
use App\CategoryProduct;
use Illuminate\Http\Request;
use App\Repositories\ProductRepository;

class CategoryController extends Controller
{
    /**
     * @var ProductRepository
     */
    protected $productRepo;

    /**
     * @param ProductRepository $productRepo
     */
    public function __construct(ProductRepository $productRepo)
    {
        $this->productRepo = $productRepo;
    }

    /**
     * @param  Request  $request
     * @param  Category $category
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function show(Request $request, Category $category)
    {
        if (! $category->exists && ! $request->ajax()) {
            return view('front.categories.list', [
                'parentCategoriesIds' => [],
                'category' => [],
            ]);
        }

        $parentCategories = Category::ancestorsAndSelf($category);

        $parentCategoriesIds = $parentCategories->pluck('id')->all();

        if (! $request->ajax()) {
            return view('front.categories.list', [
                'parentCategoriesIds' => $parentCategoriesIds,
                'category' => $category,
            ]);
        }

        $query = ! $category->exists
            ? Product::where('id', '>', 0)
            : CategoryProduct::whereIn(
                'category_id',
                Category::descendantsAndSelf($category)->pluck('id')
                            ->toArray()
            )
                ->get(['product_id'])
                ->pluck('product_id')
                ->toArray();

        if (is_array($query)) {
            $query = Product::whereIn('id', $query);
        }

        $query = $query->with(['images', 'brand', 'children']);

        if ($request->has('brand') && is_array($request->input('brand'))) {
            $query = $query->whereIn('brand_id', array_map('intval', $request->input('brand')));

            try {
                $brandNames = Brand::find($request->input('brand'))
                    ->pluck('name');
            } catch (\Exception $exception) {
                $brandNames = '';
            }
        }
    
        if ($request->has('availability_id') && is_array($request->input('availability_id'))) {
            $query = $query->where('availability_id', 1);
        }
    
        switch ($request->get('sortBy', 'relevance')) {

            case 'l-t-h':
                $query = $query->orderBy('price', 'asc');
                break;

            case 'h-t-l':
                $query = $query->orderBy('price', 'desc');
                break;
            
            default:
                $query = $query->orderBy('sales_count', 'desc');
                break;
        }

        $categoryChilds = $category->children->map(function ($item) {
            if (! isset($item->depth)) {
                $item->depth = 0;
            }

            $item->depth += 1;
            return $item->only(['slug', 'name', 'depth']);
        });

        return response()->json([
            'brandNames' => $brandNames ?? [],
            'parentCategories' => $parentCategories,
            'navItems' => $categoryChilds,
            'category' => $category,
            'maxPrice' => floatval($query->max('price')),
            'minPrice' => floatval($query->min('price')),
            'products' => $query->paginate($request->get('perPage', 24)),
        ]);
    }
}
