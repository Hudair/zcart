<?php
Route::post('role/massTrash', 'RoleController@massTrash')->name('role.massTrash')->middleware('demoCheck');
Route::post('role/massDestroy', 'RoleController@massDestroy')->name('role.massDestroy')->middleware('demoCheck');
Route::delete('role/emptyTrash', 'RoleController@emptyTrash')->name('role.emptyTrash');
Route::delete('role/{role}/trash', 'RoleController@trash')->name('role.trash'); // role move to trash
Route::get('role/{role}/restore', 'RoleController@restore')->name('role.restore');
Route::resource('role', 'RoleController');