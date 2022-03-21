<?php
Route::get('attribute/{attribute}/entities', 'AttributeController@entities')->name('attribute.entities');
Route::delete('attribute/{attribute}/trash', 'AttributeController@trash')->name('attribute.trash');
Route::get('attribute/{attribute}/restore', 'AttributeController@restore')->name('attribute.restore');
Route::post('attribute/massTrash', 'AttributeController@massTrash')->name('attribute.massTrash')->middleware('demoCheck');
Route::post('attribute/massDestroy', 'AttributeController@massDestroy')->name('attribute.massDestroy')->middleware('demoCheck');
Route::delete('attribute/emptyTrash', 'AttributeController@emptyTrash')->name('attribute.emptyTrash');
Route::post('attribute/reorder', 'AttributeController@reorder')->name('attribute.reorder');
Route::resource('attribute', 'AttributeController', ['except' => ['show']]);
