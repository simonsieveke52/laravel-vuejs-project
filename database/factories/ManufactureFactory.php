<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(App\Manufacture::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'slug' => Str::slug(Str::random(4) . '-' . time() . $faker->name),
        'cover' => $faker->text,
        'description' => $faker->text,
        'status' => $faker->boolean,
    ];
});
