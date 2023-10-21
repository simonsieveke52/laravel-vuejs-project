<?php

namespace App\Http\Controllers;

use App\Product;
use App\OrderProduct;
use App\ProductSubscription;
use Illuminate\Http\Request;
use App\Http\Requests\AddToCartRequest;
use App\Repositories\Payment\AuthorizeNetRepository;
use App\Repositories\Contracts\CartRepositoryContract;

class ProductSubscriptionController
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddToCartRequest $request)
    {
        if (! config('default-variables.subscription.status')) {
            return response()->json([
                'message' => 'This product currently not available for subsciption.'
            ], 422);
        }

        $product = Product::findOrFail($request->id);

        if (! config('default-variables.force-checkout') && (int) $product->availability_id === 0) {
            return response()->json([
                'message' => 'This product currently out of stock.'
            ], 422);
        }

        $request->request->add([
            'is_subscription' => true,
            'subscription_discount' => config('default-variables.subscription.discount'),
        ]);

        $cartItem = $this->cartRepository
                        ->addToCart($product, $request->quantity, $request->except('_token', 'id'))
                        ->get(getCartKey($product, 'subscription'));

        $cartItem = is_array($cartItem) ? $cartItem : $cartItem->all();
        $cartItem['deleted'] = false;


        return response()->json($cartItem);
    }


    /**
     * @return void
     */
    public function destroy(Request $request, OrderProduct $orderProduct)
    {
        if ((int) $orderProduct->is_subscription === 0) {
            abort(404);
        }

        if ((int) $orderProduct->subscription_id === 0) {
            abort(404);
        }

        if ((int) $orderProduct->is_active_subscription === 0) {
            return redirect()->route('home');
        }

        if ($request->ajax()) {
            try {
                $response = (new AuthorizeNetRepository())->cancelSubscription($orderProduct->subscription_id);
                $orderProduct->canceled_at = date('Y-m-d H:i:s');
                $orderProduct->is_active_subscription = false;
                $orderProduct->save();
                return response()->json(true);
            } catch (\Exception $exception) {
                return response()->json($exception->getMessage(), 422);
            }
        }
        
        return view('front.products.cancel-subscription');
    }
}
