<?php

/*
|--------------------------------------------------------------------------
| default global variables stored in this array
|--------------------------------------------------------------------------
|
*/

return [

    /**
     * Default cache life time is 1h
     */
    'cache_life_time' => 60 * 60,

    'default-image' => '/storage/notfound.png',

    'phone' => '8777678482',

    /**
     * Tax enabled
     */
    'tax_status' => true,

    /**
     * This will allow user to add products to cart even
     * if product availability is out of stock
     */
    'force-checkout' => true,

    /**
     * Google Tag manager ID
     */
    'gtm' => 'GTM-5LTBKN5',

    'pagination' => env('PAGINATION_LENGTH', 25),
    
    /**
     * Put social media links here
     */
    'social_media' => [
        'facebook' => 'https://www.facebook.com/NaturalHouseProducts',
        'youtube' => 'https://www.youtube.com/naturalhouseproducts',
        'twitter' => 'https://twitter.com/naturalhousepro',
    ],

    'subscription' => [
        'status' => true,
        'discount' => 10
    ],
];
