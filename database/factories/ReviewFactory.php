<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Review::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'title' => $faker->sentence,
        'description' => $faker->realText(mt_rand(30, 600)),
        'grade' => mt_rand(2, 5),
        'like_counter' => mt_rand(100, 1000),
        'dislike_counter' => mt_rand(10, 100),
        'report_counter' => mt_rand(10, 50),
    ];
});
