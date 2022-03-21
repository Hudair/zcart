<?php
// pages
Route::post('page/massTrash', 'PageController@massTrash')->name('page.massTrash')->middleware('demoCheck');
Route::post('page/massDestroy', 'PageController@massDestroy')->name('page.massDestroy')->middleware('demoCheck');
Route::delete('page/emptyTrash', 'PageController@emptyTrash')->name('page.emptyTrash');
Route::delete('page/{page}/trash', 'PageController@trash')->name('page.trash'); // page move to trash
Route::get('page/{page}/restore', 'PageController@restore')->name('page.restore');
Route::resource('page', 'PageController', ['except' => ['show']]);