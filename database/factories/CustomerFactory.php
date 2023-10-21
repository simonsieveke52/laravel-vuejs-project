<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Customer::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'phone' => $faker->phoneNumber,
        'password' => bcrypt($faker->password),
        'status' => $faker->boolean,
        'last_login' => $faker->dateTime(),
        'email_verified_at' => $faker->dateTime(),
        'remember_token' => Str::random(10),
    ];
});
