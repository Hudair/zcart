<?php
// Route::delete('slider/{slider}/trash', 'SliderController@trash')->name('slider.trash'); // slider post move to trash
// Route::get('slider/{slider}/restore', 'SliderController@restore')->name('slider.restore');
// Route::post('slider/massTrash', 'SliderController@massTrash')->name('slider.massTrash')->middleware('demoCheck');
Route::post('slider/massDestroy', 'SliderController@massDestroy')->name('slider.massDestroy')->middleware('demoCheck');
// Route::delete('slider/emptyTrash', 'SliderController@emptyTrash')->name('slider.emptyTrash');
Route::resource('slider', 'SliderController', ['except' => ['show']]);