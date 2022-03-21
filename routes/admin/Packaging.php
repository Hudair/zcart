<?php
// packagings
Route::post('packaging/massTrash', 'PackagingController@massTrash')->name('packaging.massTrash')->middleware('demoCheck');
Route::post('packaging/massDestroy', 'PackagingController@massDestroy')->name('packaging.massDestroy')->middleware('demoCheck');
Route::delete('packaging/emptyTrash', 'PackagingController@emptyTrash')->name('packaging.emptyTrash');
Route::delete('packaging/{packaging}/trash', 'PackagingController@trash')->name('packaging.trash'); // packaging move to trash
Route::get('packaging/{packaging}/restore', 'PackagingController@restore')->name('packaging.restore');
Route::resource('packaging', 'PackagingController', ['except' =>['show']]);
