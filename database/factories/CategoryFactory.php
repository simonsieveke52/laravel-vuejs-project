<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(App\Category::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'slug' => Str::slug(Str::random(4) . '-' . time() . $faker->name),
        'cover' => $faker->word,
        'description' => $faker->text,
        'marketing_description' => $faker->text,
        'status' => true,
        'on_navbar' => false,
        'on_filter' => $faker->boolean,
        'sort_order' => $faker->randomNumber(),
    ];
});
