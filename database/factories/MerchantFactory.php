<?php

use Carbon\Carbon;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(App\Merchant::class, function (Faker $faker) {

    $created_at = Carbon::Now()->subDays(rand(0, 15));

    return [
        'shop_id' => 1,
        'role_id' => \App\Role::MERCHANT,
        'nice_name' => $faker->lastName,
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => bcrypt(123456),
        'dob' => $faker->date,
        'sex' => $faker->randomElement(['app.male', 'app.female']),
        'description' => $faker->text(500),
        'active' => 1,
        // 'remember_token' => Str::random(10),
        // 'verification_token' => rand(0,1) == 1 ? Null : Str::random(10),
        'created_at' => $created_at,
        'updated_at' => $created_at,
    ];
});
