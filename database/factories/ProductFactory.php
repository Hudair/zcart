<?php

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Product::class, function (Faker $faker) {
    $youtube = ["MLpWrANjFbI", "TGbUpEJ1z-k", "SKbHHcdLGKw", "7abGwVjXSY4", "n7EmdNsKIhk", "mATMpaPBBFI", "AVpcxtH8hWg", "tJlzIJaokVY", "eEzD-Y97ges"];

    $vimeo = [
        "https://vimeo.com/channels/staffpicks/434009135",

        "104938952","265254094","380312199","109952031"];

    $images = [
        'https://loremflickr.com/640/360',
        'http://placeimg.com/640/360/any',
        'https://picsum.photos/640/360.webp'
    ];

    // Build description
    $desc = $faker->text($faker->randomElement([200, 400, 300]));
    $desc .= str_replace("::LINK::", $faker->randomElement($youtube), '<p><br/><iframe frameborder="0" src="//www.youtube.com/embed/::LINK::" class="note-video-clip" style="width: 100%; height: 475px; float: none;"></iframe></p><br/>');
    $desc .= $faker->text($faker->randomElement([100, 200, 300]));
    $desc .= str_replace("::LINK::", $faker->randomElement($images), '<img src="::LINK::" style="width: 50%; float: right;">');
    $desc .= $faker->text($faker->randomElement([1000, 1200, 1500, 1800]));
    $desc .= str_replace("::LINK::", $faker->randomElement($images), '<p><br/><img src="::LINK::" style="width: 100%; float: none;"></p><br/>');
    $desc .= $faker->text($faker->randomElement([400, 200, 300]));

    return [
        'shop_id' => rand(0, 1) ? 1 : Null,
        'manufacturer_id' => $faker->randomElement(\DB::table('manufacturers')->pluck('id')->toArray()),
        'brand' => $faker->word,
        'name' => $faker->sentence,
        'model_number' => $faker->word .' '.$faker->bothify('??###'),
        'mpn' => $faker->randomNumber(NULL, false),
        'gtin' => $faker->ean13,
        'gtin_type' => $faker->randomElement(\DB::table('gtin_types')->pluck('name')->toArray()),
        'description' => $desc,
        'origin_country' => $faker->randomElement(\DB::table('countries')->pluck('id')->toArray()),
        'has_variant' => $faker->boolean,
        'slug' => $faker->slug,
    	// 'meta_title' => $faker->sentence,
    	// 'meta_description' => $faker->realText,
    	'sale_count' => $faker->randomDigit,
        'active' => 1,
        'created_at' => Carbon::Now()->subDays(rand(0, 15)),
        'updated_at' => Carbon::Now()->subDays(rand(0, 15)),
    ];
});
