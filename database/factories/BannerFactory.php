<?php

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Banner::class, function (Faker $faker) {

	$desc = rand(19, 80).'% off';

	$frases = [
		'Flat ' . $desc,
		'Up to ' . $desc,
		$desc . ' today!'
	];

	$desc = $faker->randomElement($frases);
	$desc = rand(0,4) ? $desc : $desc . ' + free shipping';
	$desc = rand(0,4) ? $desc : 'Don\'t miss out!';

    return [
    	'title' => $faker->randomElement(['Deal of the day', 'Fashion accessories deals', 'Kids item deals', 'Year end SALE!', 'Black Friday Deals!', 'Books category deals', 'Free shipping', 'Tech accessories with free shipping', '80% Off!', 'Everyday essentials deals', 'Top deal on fashion accessories', 'Knockout offers!', 'BIG sale week!', 'Save up to 40%']),
        'description' => rand(0,6) ? $desc : '',
        'link' => '/category/' . $faker->randomElement(\DB::table('categories')->pluck('slug')->toArray()),
        'link_label' => 'Shop Now',
        'bg_color' => $faker->hexcolor,
        'created_at' => Carbon::Now(),
        'updated_at' => Carbon::Now(),
    ];
});
