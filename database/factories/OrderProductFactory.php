<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\OrderProduct::class, function (Faker $faker) {
    return [
        'quantity' => $faker->randomNumber(),
        'price' => $faker->randomFloat(),
        'order_id' => factory(App\Order::class),
        'product_id' => factory(App\Product::class),
        'options' => $faker->text,
        'deleted_at' => $faker->dateTime(),
    ];
});
