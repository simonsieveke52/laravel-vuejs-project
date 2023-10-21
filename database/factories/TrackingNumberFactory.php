<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\TrackingNumber::class, function (Faker $faker) {
    return [
        'order_id' => factory(App\Order::class),
        'shipment_id' => $faker->randomNumber(),
        'number' => $faker->word,
        'file_name' => $faker->word,
        'shipment_cost' => $faker->randomFloat(),
        'insurance_cost' => $faker->randomFloat(),
        'carrier_code' => $faker->word,
        'carrier_name' => $faker->word,
        'user_file_id' => factory(App\UserFile::class),
    ];
});
