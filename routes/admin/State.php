<?php
Route::get('state/create/{country}', 'StateController@create')->name('state.create');

Route::post('state/massDestroy', 'StateController@massDestroy')->name('state.massDestroy')->middleware('demoCheck');

Route::resource('state', 'StateController', ['except' => ['index', 'create']]);
