<?php

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Message::class, function (Faker $faker) {

    $shop_id = rand(1, 2);

    return [
        // 'shop_id' => $faker->randomElement(\DB::table('shops')->pluck('id')->toArray()),
        // 'customer_id' => $faker->randomElement(\DB::table('customers')->pluck('id')->toArray()),
        'shop_id' => $shop_id,
        'customer_id' => 1,
        'order_id' => $faker->randomElement(\DB::table('orders')->where('shop_id', $shop_id)->pluck('id')->toArray()),
        'subject' => $faker->sentence,
        'message' => $faker->paragraph,
        'status' => rand(1, 3),
        'label' => rand(1, 5),
        'created_at' => Carbon::Now()->subDays(rand(0, 15)),
        'updated_at' => Carbon::Now()->subDays(rand(0, 15)),
    ];
});
