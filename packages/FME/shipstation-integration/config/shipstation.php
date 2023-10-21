<?php

return [
    'cache' => true,
    'fromPostalCode' => '90505',
    'apiKey' => '6dec12542df044608039dd1d14567700',
    'apiSecret' => 'e16a621314da4352aa49d08b58405e0a',
    'shipFrom' =>  [
        "name" => "Advancedworld LLC",
        "company" => "Advancedworld",
        "street1" => "3055 Kashiwa Street",
        "street2" => null,
        "street3" => null,
        "city" => "Torrance",
        "state" => "CA",
        "postalCode" => "90505",
        "country" => "US",
        "phone" => config('default-variables.phone'),
        "residential" => false
    ],
    'free_shipping' => (Object) [
        'name' => 'FREE Shipping',
        'code' => 'free_shipping',
        'carrierCode' => "FREE Shipping",
        'otherCost' => 0,
        'serviceCode' => "free_shipping",
        'serviceName' => "Free Shipping",
        'shipmentCost' => 0
    ]
];
