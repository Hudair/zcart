<?php

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Visitor::class, function (Faker $faker) {
	$num2 = rand(1, 999);
    $time = Carbon::Now()->subMonths(rand(0, 6));

    return [
    	'ip' => $faker->unique()->ipv4,
    	'mac' => $faker->unique()->macAddress,
        'hits' => $num2,
        'page_views' => $num2 + rand(0, 999),
        'country_code' => $faker->countryCode,
        'created_at' => $time,
        'updated_at' => $time,
    ];
});
