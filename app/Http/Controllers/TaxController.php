<?php

namespace App\Http\Controllers;

use App\Zipcode;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateTaxRequest;
use App\Repositories\Contracts\CartRepositoryContract;

class TaxController extends Controller
{
    /**
     * @var CartRepositoryContract
     */
    protected $cartRepository;

    /**
     * @param CartRepositoryContract $cartRepository
     */
    public function __construct(CartRepositoryContract $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    /**
     * Update tax on cart
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Zipcode $zipcode): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'zipcode' => 'required|exists:zipcodes,name'
        ]);

        $this->cartRepository->setTax($zipcode->tax_rate);

        return response()->json($zipcode->tax_rate);
    }
}
