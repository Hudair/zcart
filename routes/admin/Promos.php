<?php
    //Metrics / Key Performance Indicators...
    //Show all promotions
    Route::get('promotions/', 'PromotionsController@index')->name('promotions');

    //Deal of the day:
    Route::get('promotions/dealOfTheDay/edit', 'PromotionsController@editDealOfTheDay')->name('promotion.dealOfTheDay');
    Route::put('promotions/dealOfTheDay/update', 'PromotionsController@updateDealOfTheDay')->name('promotion.dealOfTheDay.update');

    //Featured Products:
    Route::get('promotions/featuredItems/edit', 'PromotionsController@editFeaturedItems')->name('featuredItems.edit');
    Route::put('promotions/featuredItems/update', 'PromotionsController@updateFeaturedItems')->name('update.featuredItems');

    //Tagline
    Route::get('promotions/tagline/edit', 'PromotionsController@editTagline')->name('promotion.tagline');
    Route::put('promotions/tagline/update', 'PromotionsController@updateTagline')->name('promotion.tagline.update');

    //Tagline
    Route::get('promotions/bestFinds/edit', 'PromotionsController@editBestFinds')->name('promotion.bestFindsUnder');
    Route::put('promotions/bestFinds/update', 'PromotionsController@updateBestFinds')->name('promotion.bestFindsUnder.update');
