<?php
Route::get('country/{country}/state', 'CountryController@states')->name('country.states');

// Route::post('country/massDestroy', 'CountryController@massDestroy')->name('country.massDestroy')->middleware('demoCheck');

Route::resource('country', 'CountryController', ['except'=>'show','destroy']);
