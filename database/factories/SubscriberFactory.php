<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Subscriber::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'subscribe' => $faker->boolean,
        'deleted_at' => $faker->dateTime(),
    ];
});
