<?php
Route::post('currency/massDestroy', 'CurrencyController@massDestroy')->name('currency.massDestroy')->middleware('demoCheck');
Route::resource('currency', 'CurrencyController', ['except'=>'show']);
