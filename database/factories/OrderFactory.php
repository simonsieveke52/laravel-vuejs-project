<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Order::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'phone' => $faker->phoneNumber,
        'payment_method' => 'Credit card',
        'shipping_cost' => mt_rand(10, 40),
        'subtotal' => mt_rand(10, 40),
        'total' => mt_rand(10, 40),
        'order_status_id' => 1,
        'confirmed' => mt_rand(0, 1),
        'confirmed_at' => $faker->dateTime(),
        'mailed' => $faker->boolean,
        'mailed_at' => $faker->dateTime(),
    ];
});
