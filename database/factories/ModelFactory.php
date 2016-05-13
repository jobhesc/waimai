<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'mobile' => $faker->randomElement(['130', '131', '133', '135', '136', '137', '138', '139']).$faker->numberBetween(10000000, 99999999),
        'token' => $faker->md5,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Shop::class, function(Faker\Generator $faker){
    return [
        'name' => $faker->company,
        'desc' => $faker->text,
        'location_lat' => $faker->latitude,
        'location_lng' => $faker->longitude,
        'location_address' => $faker->address,
        'work_time' => '09:00-22:00',
        'min_price' => $faker->numberBetween(0, 20),
        'charge' => $faker->numberBetween(0, 10),
        'notice' => $faker->text,
        'phone' => $faker->randomElement(['130', '131', '133', '135', '136', '137', '138', '139']).$faker->numberBetween(10000000, 99999999),
        'delivery_kind' => $faker->randomElement(['self', 'me', 'third']),
        'support_cod' => $faker->boolean(),
        'img' => $faker->imageUrl(),
    ];
});

