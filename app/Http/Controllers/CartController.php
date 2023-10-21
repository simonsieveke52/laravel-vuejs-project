<?php

namespace App\Http\Controllers;

use App\Product;
use App\Discount;
use Illuminate\Http\Request;
use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\UpdateCartRequest;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Contracts\CartRepositoryContract;

class CartController extends Controller
{
    /**
     * Cart repository
     *
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'cartItems' => $this->cartRepository->getMappedCartItems(),
            'subtotal' => $this->cartRepository->getSubTotal(),
            'shipping' => $this->cartRepository->getShipping(),
            'discount' => $this->cartRepository->getDiscountValue(),
            'taxRate' => $this->cartRepository->getTaxRate(),
            'total' => $this->cartRepository->getTotal(),
            'tax' => $this->cartRepository->getTax(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AddToCartRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AddToCartRequest $request): \Illuminate\Http\JsonResponse
    {
        $product = Product::findOrFail($request->id);

        if (! config('default-variables.force-checkout') && (int) $product->availability_id === 0) {
            return response()->json([
                'message' => 'This product currently out of stock.'
            ], 422);
        }

        // create new cart item
        $cartItem = $this->cartRepository
                        ->addToCart($product, $request->quantity, $request->except('_token', 'id'))
                        ->get(getCartKey($product, 'direct'));

        $cartItem = is_array($cartItem) ? $cartItem : $cartItem->all();
        $cartItem['deleted'] = false;


        return response()->json($cartItem);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCartRequest $request
     * @param int  $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateCartRequest $request, $id): \Illuminate\Http\JsonResponse
    {
        if ($this->cartRepository->updateQuantityInCart($id, $request->quantity)) {
            return response()->json(true, 200);
        }

        return response()->json(false, 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int  $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $this->cartRepository->remove($id);

        if ($this->cartRepository->isEmpty()) {
            $this->cartRepository->clear();
            session()->forget('shipping');
        }

        return response()->json($id);
    }
    
    /**
     * Get the active Coupon Code.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCouponCode()
    {
        return $this->cartRepository->getDiscount();
    }

    /**
     * Apply the Coupon Code.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function applyCouponCode($code): \Illuminate\Http\JsonResponse
    {
        $discount = Discount::where('coupon_code', $code)->firstOrFail();

        if (! $discount->isValid()) {
            return response()->json(false, 500);
        }

        $this->cartRepository->setDiscount($discount);

        return response()->json(true);
    }
}
