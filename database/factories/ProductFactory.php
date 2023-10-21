<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(App\Product::class, function (Faker $faker) {
    $cost = mt_rand(10, 30);

    return [
        'item_id' => $faker->word,
        'name' => $faker->name,
        'slug' => Str::slug(Str::random(4) . '-' . time() . $faker->name),
        'homepage_order' => $faker->randomNumber(),
        'availability_id' => mt_rand(1, 3) > 1 ? 1 : mt_rand(1, 3),
        'sku' => $faker->lexify('?????'),
        'mpn' => $faker->word,
        'upc' => $faker->word,
        'cost' => $cost,
        'price' => round($cost * 1.3, 2),
        'status' => true,
        'is_on_feed' => $faker->boolean,
        'quantity' => $faker->randomNumber(),
        'quantity_per_case' => $faker->word,
        'short_description' => $faker->text,
        'searchable_text' => $faker->text,
        'description' => $faker->text,
        'weight_uom' => $faker->word,
        'length_uom' => $faker->word,
        'height_uom' => $faker->word,
        'width_uom' => $faker->word,
        'weight' => mt_rand(2, 10),
        'width' => mt_rand(2, 10),
        'height' => mt_rand(2, 10),
        'length' => mt_rand(2, 10),
        'country_id' => 1,
    ];
});
