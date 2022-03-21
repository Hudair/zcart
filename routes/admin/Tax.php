<?php
// taxes
Route::post('tax/massTrash', 'TaxController@massTrash')->name('tax.massTrash')->middleware('demoCheck');
Route::post('tax/massDestroy', 'TaxController@massDestroy')->name('tax.massDestroy')->middleware('demoCheck');
Route::delete('tax/emptyTrash', 'TaxController@emptyTrash')->name('tax.emptyTrash');
Route::delete('tax/{tax}/trash', 'TaxController@trash')->name('tax.trash'); // tax move to trash
Route::get('tax/{tax}/restore', 'TaxController@restore')->name('tax.restore');
Route::resource('tax', 'TaxController',['except' => 'show']);
