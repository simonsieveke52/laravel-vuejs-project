<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\View\View
     */
    public function show(): \Illuminate\View\View
    {
        return view('front.pages.favorites');
    }
}
