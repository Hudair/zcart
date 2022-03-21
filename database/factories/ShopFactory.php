<?php

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Shop::class, function (Faker $faker) {
    $company = $faker->unique->company;
    $merchnats = \DB::table('users')->where('role_id', \App\Role::MERCHANT)->where('id', '>', 4)->pluck('id')->toArray();
    $created_at = Carbon::Now()->subDays(rand(0, 15));

    return [
        'owner_id' => empty($merchnats) ? Null : $faker->randomElement($merchnats),
        'name' => $company,
        'legal_name' => $company,
        'slug' => $faker->slug,
        'email' => $faker->email,
        'description' => $faker->text(500),
        'external_url' => $faker->url,
        'timezone_id' => $faker->randomElement(\DB::table('timezones')->pluck('id')->toArray()),
        'active' => $faker->boolean,
        'created_at' => $created_at,
        'updated_at' => $created_at,
    ];
});
