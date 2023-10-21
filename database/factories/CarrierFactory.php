<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Carrier::class, function (Faker $faker) {
    return [
        'order_id' => $faker->randomNumber(),
        'service_name' => $faker->word,
        'service_code' => $faker->word,
        'carrier_code' => $faker->word,
        'shipment_cost' => $faker->randomFloat(),
        'other_cost' => $faker->randomFloat(),
        'status' => $faker->boolean,
    ];
});
