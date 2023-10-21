<?php

namespace App\Http\Controllers;

use App\Quote;
use Illuminate\Http\Request;
use App\Http\Requests\StoreQuoteRequest;

class QuoteController extends Controller
{
    /**
     * @param StoreQuoteRequest $request
     *
     * @return Quote
     */
    public function store(StoreQuoteRequest $request): Quote
    {
        return Quote::create($request->only([
            'name', 'email', 'phone', 'content'
        ]));
    }
}
