<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Shipping::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->text,
        'base_cost' => $faker->randomFloat(),
        'status' => $faker->boolean,
    ];
});
