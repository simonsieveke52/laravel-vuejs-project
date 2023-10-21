<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\ProductImage::class, function (Faker $faker) {
    return [
        'src' => $faker->word,
        'product_id' => factory(App\Product::class),
        'is_transparent' => $faker->boolean,
        'is_main' => $faker->boolean,
        'status' => $faker->boolean,
    ];
});
