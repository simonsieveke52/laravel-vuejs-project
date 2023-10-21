<?php

namespace App\Http\Controllers;

use App\Brand;
use Illuminate\Http\Request;
use App\Repositories\BrandRepository;

class BrandController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param Brand $brand
     *
     * @return \Illuminate\View\View
     */
    public function show(Brand $brand): \Illuminate\View\View
    {
        $products = $brand->products()->with('images')->paginate();

        return view('front.brands.list', [
            'brand' => $brand,
            'products' => $products,
        ]);
    }
}
