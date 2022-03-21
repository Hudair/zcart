<?php
Route::delete('categoryGroup/{categoryGrp}/trash', 'CategoryGroupController@trash')->name('categoryGroup.trash');
Route::get('categoryGroup/{categoryGrp}/restore', 'CategoryGroupController@restore')->name('categoryGroup.restore');
Route::post('categoryGroup/massTrash', 'CategoryGroupController@massTrash')->name('categoryGroup.massTrash');
Route::post('categoryGroup/massDestroy', 'CategoryGroupController@massDestroy')->name('categoryGroup.massDestroy');
Route::delete('categoryGroup/emptyTrash', 'CategoryGroupController@emptyTrash')->name('categoryGroup.emptyTrash');
Route::resource('categoryGroup', 'CategoryGroupController', ['except' => ['show']]);