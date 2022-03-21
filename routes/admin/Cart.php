<?php
Route::post('cart/massTrash', 'CartController@massTrash')->name('cart.massTrash')->middleware('demoCheck');
Route::post('cart/massDestroy', 'CartController@massDestroy')->name('cart.massDestroy')->middleware('demoCheck');
Route::delete('cart/emptyTrash', 'CartController@emptyTrash')->name('cart.emptyTrash');
Route::delete('cart/{cart}/trash', 'CartController@trash')->name('cart.trash'); // cart move to trash
Route::get('cart/{cart}/restore', 'CartController@restore')->name('cart.restore');
Route::resource('cart', 'CartController',['except'=>['create','edit']]);
