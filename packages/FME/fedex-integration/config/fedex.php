<?php

return [
    // enable request caching
    'cache' => true,
    'sandbox' => env('FEDEX_SANDBOX', false),
    'services' => [
        [
            'key' => 'FEDEX_GROUND',
            'label' => 'FedEx Ground',
            'Code' => '92',
            'Description' => 'FedEx Ground',
        ],
        [
            'key' => 'FEDEX_2_DAY',
            'label' => 'FedEx 2 DAY',
            'Code' => '03',
            'Description' => 'FedEx 2 DAY',
        ],
        [
            'key' => 'STANDARD_OVERNIGHT',
            'label' => 'Standard Overnight',
            'Code' => '05',
            'Description' => 'Standard Overnight',
        ]
    ],
    'key' => env('FEDEX_KEY'),
    'password' => env('FEDEX_PASSWORD'),
    'account_number' => env('FEDEX_ACCOUNT_NUMBER'),
    'meter_number' => env('FEDEX_METER_NUMBER'),
    
    'shipFrom' =>  [
        "name" => "Aquagenics Technologies Inc.",
        "company" => "Pacific Sands",
        'email' => 'orders@naturalhouse.com',
        "street1" => "2764 Golfview Drive",
        "street2" => null,
        "city" => "Naperville",
        "state" => "IL",
        "postalCode" => "60563",
        "country" => "US",
        "phone" => config('default-variables.phone'),
        "residential" => false
    ]
];
