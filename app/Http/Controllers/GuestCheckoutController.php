<?php

namespace App\Http\Controllers;

use App\Address;
use App\Discount;
use App\Shipping;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Events\OrderCreateEvent;
use App\Repositories\CartRepository;
use App\Repositories\CheckoutRepository;
use App\Http\Requests\GuestCheckoutRequest;
use Darryldecode\Cart\Facades\CartFacade as Cart;

class GuestCheckoutController extends CheckoutBaseController
{
    /**
     * show checkout page
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index()
    {
        $this->cartRepository->checkItemsStock();

        if ($this->cartRepository->getCartItems()->isEmpty()) {
            return redirect()->route('home')->with('message', 'Your cart is empty.');
        }

        return view('front.guest.checkout');
    }

    /**
     * Create new order then redirect user to checkout.execute
     * Route where payment are processed and confirmed
     *
     * @param GuestCheckoutRequest $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(GuestCheckoutRequest $request)
    {
        $data = $request->except('_token');

        $this->cartRepository->checkItemsStock();

        $cartItems = $this->cartRepository->getCartItemsTransformed();

        if ($cartItems->isEmpty()) {
            return redirect()->route('guest.checkout.index');
        }

        // Create customer order with required attributes
        $order = $this->orderRepository->createOrder($data);

        // Build order details
        $this->orderRepository->buildOrderDetails($order, $cartItems);

        // associate address to current order
        $order->addresses()->saveMany([
            $this->addressRepository->createValidatedAddress($data['validatedAddress']),
            $this->addressRepository->createBillingAddress($data),
        ]);

        // Customer has shipping address diffrent than billing address
        // need to create shipping address and create order relation
        if ($request->boolean('shipping_address_different') === true) {
            $order->addresses()->save(
                $this->addressRepository->createShippingAddress($data)
            );
        }

        $discount = $this->cartRepository->getDiscount();

        if ($discount instanceof Discount) {
            $order->discount()->associate($discount);
        }

        session(['order' => $order->id]);

        return response()->json(true);
    }
}
