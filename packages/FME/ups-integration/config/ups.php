<?php

return [
    // enable request caching
    'cache' => true,
    'sandbox' => false,
    'accessKey' => '8D7FCBB32D3C48D5',
    'userId' => 'Snegron',
    'password' => 'tools5874!@',
    'services' => [
        [
            'label' => 'UPS 2nd Day Air',
            'Code' => '02',
            'Description' => 'UPS 2nd Day Air',
            'shipstation_carrier_code' => 'ups_walleted',
            'shipstation_service_code' => 'ups_2nd_day_air',
        ],
        [
            'label' => 'UPS Ground',
            'Code' => '03',
            'Description' => 'UPS Ground',
            'shipstation_carrier_code' => 'ups_walleted',
            'shipstation_service_code' => 'ups_2nd_day_air',
        ],
    ],
    'shipFrom' =>  [
        "name" => "Aquagenics Technologies Inc.",
        "company" => "Pacific Sands",
        'email' => 'orders@naturalhouse.com',
        "street1" => "2764 Golfview Drive",
        "street2" => null,
        "street3" => null,
        "city" => "Naperville",
        "state" => "IL",
        "postalCode" => "60563",
        "country" => "US",
        "phone" => config('default-variables.phone'),
        "residential" => false
    ]
];
