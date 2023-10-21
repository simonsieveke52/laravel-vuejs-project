<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['urlPreTraitement']], function () {

    /**
     * Backend routes
     */
    Route::group(['prefix' => 'fme-admin'], function () {
        Voyager::routes();
    });

    /**
     * Frontend routes
     */
    Route::namespace('Customer')->group(function () {
        Auth::routes();
        Route::get('logout', 'Auth\LoginController@logout')->name('customer.logout');
    });

    // Sitemap
    Route::get('/sitemap', 'SitemapController@index')->name('sitemap');

    Route::group(['middleware' => ['auth:customer', 'urlPreTraitement']], function () {

        // customer routes
        Route::namespace('Customer')->group(function () {
            Route::get('account', 'AccountController@index')->name('customer.account');
            Route::resource('/customer.address', 'CustomerAddressController')->only(['index', 'create', 'store', 'destroy']);
            Route::any('customer/address', 'CustomerAddressController@update')->name('customer.address.update');
            Route::get('account-subscription/{orderProduct}', 'ProductSubscriptionController@destroy')->name('account-subscription.destroy');
        });

        Route::get('checkout', 'CheckoutController@index')->name('checkout.index');
        Route::post('checkout', 'CheckoutController@store')->name('checkout.store');
    });

    Route::group(['middleware' => ['checkoutValidation']], function () {
        Route::get('shipping', 'ShippingController@index')->name('shipping.index');
        Route::put('shipping', 'ShippingController@update')->name('shipping.update');
        Route::get('confirm', 'CheckoutBaseController@confirm')->name('checkout.confirm');
        Route::post('execute', 'CheckoutBaseController@execute')->name('checkout.execute');
        Route::get('success', 'CheckoutBaseController@success')->name('checkout.success');
        Route::get('invoice/{order?}', 'InvoiceController@show')->name('invoice.show');
    });

    Route::group(['middleware' => ['urlPreTraitement']], function () {
        Route::group(['middleware' => []], function () {
            Route::get('/', 'HomeController@index')->name('home');
            Route::get('privacy-policy', 'HomeController@privacy')->name('privacy-policy');
            Route::get('terms-and-conditions', 'HomeController@terms')->name('terms-and-conditions');
            Route::get('contact', 'HomeController@contact')->name('contact-us');
            Route::get('wholesale', 'HomeController@wholesale')->name('wholesale');
            Route::get('about-us', 'HomeController@aboutUs')->name('about-us');
            Route::get('technology', 'HomeController@technology')->name('technology');
            Route::get('in-the-press', 'HomeController@inThePress')->name('in-the-press');
        });

        Route::group(['middleware' => ['guest.cart:customer']], function () {
            Route::get('guest-checkout', 'GuestCheckoutController@index')->name('guest.checkout.index');
            Route::post('guest-checkout', 'GuestCheckoutController@store')->name('guest.checkout.store');
        });
        
        Route::resource('cart', 'CartController');

        Route::post('cart-subscription', 'ProductSubscriptionController@store')->name('product-subscription.store');
        Route::get('/subscription/{orderProduct}', 'ProductSubscriptionController@destroy')
            ->name('subscription.destroy')
            ->middleware('throttle:5,1')
            ->middleware('signed');

        Route::post('address-validation', 'AddressValidationController@store')->name('address-validation.store');
        Route::get('cart/couponcode/show', 'CartController@getCouponCode')->name('getCouponCode');
        Route::get('cart/couponcode/{couponcode}', 'CartController@applyCouponCode')->name('applyCouponCode');
        Route::put('tax/{zipcode?}', 'TaxController@update')->name('tax.update');

        Route::resource('brand', 'BrandController')->only(['index', 'show']);

        Route::group(['middleware' => []], function () {
            Route::post("options/{product}", 'ProductController@index')->name('product.index');
            Route::get("search", 'ProductController@search')->name('product.search');
        });
        
        Route::post("search", 'ProductController@search')->name('ajax.product.search');
        Route::get('/favorites', 'FavoritesController@show')->name('favorites');

        Route::post('quote', 'QuoteController@store')->name('quote.store');
        Route::post('contact', 'ContactRequestController@store')->name('contact.store');

        Route::post('review/{product}', 'ReviewController@store')->name('review.store');
        Route::get('review/{product}', 'ReviewController@show')->name('review.show');
        Route::put('review/{review}', 'ReviewController@update')->name('review.update')->middleware('throttle:10,1');

        Route::group(['middleware' => []], function () {
            Route::get('category/{category?}/', 'CategoryController@show')
                ->where('category', '(.*)')
                ->name('category.filter');

            Route::post('category/{category?}', 'CategoryController@show')->name('category-ajax.filter');
            Route::get('{product?}', 'ProductController@show')->name('product.show');
        });
    });
});
