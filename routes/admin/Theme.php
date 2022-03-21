<?php
	// Theme
	Route::get('/theme', 'ThemeController@all')->name('theme.index');
	Route::put('/theme/activate/{theme}/{type?}', 'ThemeController@activate')->name('theme.activate');

	// Theme Options
	Route::get('/theme/option', 'ThemeOptionController@index')->name('theme.option');
	Route::get('/theme/featuredCategories', 'ThemeOptionController@featuredCategories')->name('featuredCategories');
	Route::put('/theme/update/featuredCategories', 'ThemeOptionController@updateFeaturedCategories')->name('update.featuredCategories');

	//Featured Brands
	Route::get('/theme/featuredBrands', 'ThemeOptionController@featuredBrands')->name('featuredBrands');
	Route::put('/theme/update/featuredBrands', 'ThemeOptionController@updateFeaturedBrands')->name('update.featuredBrands');

	//Trending Now Categories
	Route::get('/theme/trendingNow', 'ThemeOptionController@editTrendingNow')->name('trendingNow');
	Route::put('/theme/update/trendingNow', 'ThemeOptionController@updateTrendingNow')->name('update.trendingNow');

