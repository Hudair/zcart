<?php
Route::delete('category/{category}/trash', 'CategoryController@trash')->name('category.trash'); // category post move to trash
Route::post('category/massTrash', 'CategoryController@massTrash')->name('category.massTrash');
Route::post('category/massDestroy', 'CategoryController@massDestroy')->name('category.massDestroy');
Route::delete('category/emptyTrash', 'CategoryController@emptyTrash')->name('category.emptyTrash');
Route::get('category/{category}/restore', 'CategoryController@restore')->name('category.restore');
Route::resource('category', 'CategoryController', ['except' => ['show']]);
