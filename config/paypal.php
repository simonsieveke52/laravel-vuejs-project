<?php

/**
 * Here all configuration required in Paypal payments
 * For the mode, use eather live or sandbox
 *
 */
return [
    /**
     * Sandbox creds
     *
     */
    'local' => [
        'account_id' => 'sb-qjpim2983289@business.example.com',
        'client_id' => 'AQYr64bwzhpJAV1g5qzuPo2BhJuOPAdh3Gd3kez0y5gaIY3EDujR-nKwl7DCtQ_368B_DoJhPNcaEgA3',
        'client_secret' => 'EHExTla8xsM3k7w4dJtuAbUsZUTP_dlsXS923EekS7evshJnYyeqxAon0iSwMuyH-gQygcX4m1uSVNdb',
        'mode' => 'sandbox'
    ],

    /**
     * Production creds
     *
     */
    'production' => [
        'account_id' => 'sb-qjpim2983289@business.example.com',
        'client_id' => 'AQYr64bwzhpJAV1g5qzuPo2BhJuOPAdh3Gd3kez0y5gaIY3EDujR-nKwl7DCtQ_368B_DoJhPNcaEgA3',
        'client_secret' => 'EHExTla8xsM3k7w4dJtuAbUsZUTP_dlsXS923EekS7evshJnYyeqxAon0iSwMuyH-gQygcX4m1uSVNdb',
        'mode' => 'sandbox'
    ]
];
