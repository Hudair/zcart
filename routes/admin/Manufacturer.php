<?php
Route::delete('manufacturer/{manufacturer}/trash', 'ManufacturerController@trash')->name('manufacturer.trash');
Route::post('manufacturer/massTrash', 'ManufacturerController@massTrash')->name('manufacturer.massTrash')->middleware('demoCheck');
Route::post('manufacturer/massDestroy', 'ManufacturerController@massDestroy')->name('manufacturer.massDestroy')->middleware('demoCheck');
Route::delete('manufacturer/emptyTrash', 'ManufacturerController@emptyTrash')->name('manufacturer.emptyTrash');
Route::get('manufacturer/{manufacturer}/restore', 'ManufacturerController@restore')->name('manufacturer.restore');
Route::resource('manufacturer', 'ManufacturerController');