<?php

namespace App\Http\Controllers;

use App\Order;
use App\Zipcode;
use App\Shipping;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Events\OrderCreateEvent;
use App\Repositories\CartRepository;
use App\Repositories\OrderRepository;
use App\Repositories\AddressRepository;
use App\Repositories\OrderProductRepository;
use App\Http\Requests\ConfirmCheckoutRequest;
use App\Http\Requests\ExecuteCheckoutRequest;
use App\Repositories\Payment\PaypalRepository;
use App\Repositories\Payment\AuthorizeNetRepository;
use App\Repositories\Contracts\CartRepositoryContract;

class CheckoutBaseController extends Controller
{
    /**
     * @var CartRepositoryContract
     */
    protected $cartRepository;
    
    /**
     * @var OrderRepository
     */
    protected $orderRepository;

    /**
     * @var PaypalRepository
     */
    protected $paypalRepository;

    /**
     * @var AddressRepository
     */
    protected $addressRepository;

    /**
     * @var AuthorizeNetRepository
     */
    protected $authorizeNetRepository;
    
    /**
     * Checkout base controller contains all
     * Required methods to create orders
     *
     * @param OrderRepository        $orderRepository
     * @param PaypalRepository       $paypalRepository
     * @param AddressRepository      $addressRepository
     * @param AuthorizeNetRepository $authorizeNetRepository
     * @param CartRepositoryContract $cartRepository
     */
    public function __construct(
        OrderRepository $orderRepository,
        PaypalRepository $paypalRepository,
        AddressRepository $addressRepository,
        AuthorizeNetRepository $authorizeNetRepository,
        CartRepositoryContract $cartRepository
    ) {
        $this->cartRepository = $cartRepository;
        $this->orderRepository = $orderRepository;
        $this->addressRepository = $addressRepository;
        $this->authorizeNetRepository = $authorizeNetRepository;
        $this->paypalRepository = $paypalRepository;
    }

    /**
     * Execute payment
     *
     * @param ExecuteCheckoutRequest $request
     *
     * @return Illuminate\Http\JsonResponse|string
     */
    public function execute(ExecuteCheckoutRequest $request)
    {
        $order = Order::findOrFail(session('order'));

        if (! session()->has('shipping') && ! is_object(session('shipping'))) {
            return back()->with('error', 'Invalid shipping option');
        }

        $selectedShipping = session('shipping');

        try {
            $order = $this->orderRepository->updateOrder($order, $request->all(), $selectedShipping);
            $order->refresh();
        } catch (\Exception $exception) {
            return response()->json([
                'message'=> 'Invalid shipping option'
            ], 422);
        }

        // if user select to pay with paypal process method will get redirect url
        // all payment process is done within paypal sandbox or live api
        // if anything went wrong user is returned to checkout page
        if ($order->payment_method === 'paypal') {
            $this->paypalRepository->init();

            $returnUrl = $order->isValidForPaypalSubscription()
                ? $this->paypalRepository->createSubscription($order, (int) setting('subscription.frequency', 30))
                : $this->paypalRepository->process($order);

            return response()->json($returnUrl);
        }

        try {
            $this->authorizeNetRepository->process($order);
            $this->cartRepository->clear();
            return response()->json(route('checkout.success'));
        } catch (\Exception $exception) {
            return response()->json([
                'message'=> $exception->getMessage()
            ], 422);
        }
    }

    /**
     * Confirm paypal payment
     *
     * @param  Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirm(Request $request)
    {
        $order = Order::findOrFail(session('order'));
        
        if ($order->payment_method !== 'paypal') {
            return redirect()->back();
        }

        try {
            $order->isValidForPaypalSubscription()
                ? $this->paypalRepository->executeAgreement($request, $order)->confirm($order)
                : $this->paypalRepository->check($request)->confirm($order);

            $this->cartRepository->clear();
            return redirect()->route('checkout.success');
        } catch (\Exception $exception) {
            return response()->json([
                'message'=> $exception->getMessage()
            ], 422);
        }
    }

    /**
     * Get success page
     *
     * @return \Illuminate\View\View
     */
    public function success(): \Illuminate\View\View
    {
        $order = Order::findOrFail(session('order'));

        $this->orderRepository->confirmOrder($order);
        
        session()->forget('order');
        session()->forget('shipping');
        
        event(new OrderCreateEvent($order));

        return view('front.checkout.success', [
            'order' => $order
        ]);
    }
}
