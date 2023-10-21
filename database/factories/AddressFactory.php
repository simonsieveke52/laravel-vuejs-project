<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Address::class, function (Faker $faker) {
    return [
        'address_1' => $faker->text,
        'address_2' => $faker->text,
        'zipcode' => $faker->word,
        'city' => $faker->city,
        'city_id' => $faker->randomNumber(),
        'state_id' => factory(App\State::class),
        'country_id' => factory(App\Country::class),
        'customer_id' => $faker->randomNumber(),
        'order_id' => factory(App\Order::class),
        'status' => $faker->boolean,
        'type' => $faker->randomElement(['billing', 'shipping']),
    ];
});
