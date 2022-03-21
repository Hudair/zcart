<?php
// carriers
Route::post('carrier/massTrash', 'CarrierController@massTrash')->name('carrier.massTrash')->middleware('demoCheck');
Route::post('carrier/massDestroy', 'CarrierController@massDestroy')->name('carrier.massDestroy')->middleware('demoCheck');
Route::delete('carrier/emptyTrash', 'CarrierController@emptyTrash')->name('carrier.emptyTrash');
Route::delete('carrier/{carrier}/trash', 'CarrierController@trash')->name('carrier.trash'); // carrier move to trash
Route::get('carrier/{carrier}/restore', 'CarrierController@restore')->name('carrier.restore');
Route::resource('carrier', 'CarrierController');
