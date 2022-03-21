<?php
// languages
Route::post('language/massTrash', 'LanguageController@massTrash')->name('language.massTrash')->middleware('demoCheck');
Route::post('language/massDestroy', 'LanguageController@massDestroy')->name('language.massDestroy')->middleware('demoCheck');
Route::delete('language/emptyTrash', 'LanguageController@emptyTrash')->name('language.emptyTrash');
Route::delete('language/{language}/trash', 'LanguageController@trash')->name('language.trash'); // language move to trash
Route::get('language/{language}/restore', 'LanguageController@restore')->name('language.restore');
Route::resource('language', 'LanguageController', ['except' => ['show']]);