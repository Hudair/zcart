<?php

use Carbon\Carbon;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(App\Customer::class, function (Faker $faker) {
    $time = Carbon::Now()->subMonths(rand(12, 25));

    return [
        'nice_name' => $faker->lastName,
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => bcrypt('123456'),
        'dob' => $faker->date,
        'sex' => $faker->randomElement(['app.male', 'app.female', 'app.other']),
        'description' => $faker->text(200),
        'active' => 1,
        'remember_token' => Str::random(10),
        'verification_token' => Null,
        'created_at' => $time,
        'updated_at' => $time,
    ];
});
