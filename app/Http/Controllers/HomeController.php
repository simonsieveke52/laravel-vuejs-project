<?php

namespace App\Http\Controllers;

use App\Order;
use App\Product;
use App\Category;
use App\Subscriber;
use App\OrderProduct;
use FME\Fedex\FedexFacade;
use Illuminate\Support\Arr;
use App\SubscriptionHistory;
use Illuminate\Http\Request;
use App\Mail\SubscriberCreated;
use App\Repositories\CartRepository;
use App\Jobs\CreatePaymentSubscriptionJob;
use App\Repositories\Payment\PaypalRepository;
use App\Notifications\SubscriptionProcessedNotification;

class HomeController
{
    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('front.home', [
            'categories' => Category::onHome()->take(4)->get(),
            'products' => Product::with(['images', 'children'])
                ->bestSeller()
                ->parents()
                ->take(4)
                ->get()
        ]);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function privacy()
    {
        return view('front.pages.privacy-policy');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function terms()
    {
        return view('front.pages.terms-and-conditions');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function aboutUs()
    {
        return view('front.pages.about-us');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function technology()
    {
        return view('front.pages.technology');
    }
    
    /**
     * @return \Illuminate\View\View
     */
    public function wholesale()
    {
        return view('front.pages.wholesale');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function inThePress()
    {
        return view('front.pages.in-the-press');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function faq()
    {
        return view('front.pages.faq');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function contact()
    {
        return view('front.pages.contact-us');
    }
    
    /**
     * @return \Illuminate\View\View
     */
    public function shippingReturnPolicty()
    {
        return view('front.pages.shipping-return-policy');
    }
}
