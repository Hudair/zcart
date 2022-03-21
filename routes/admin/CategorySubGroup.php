<?php
Route::delete('categorySubGroup/{categorySubGroup}/trash', 'CategorySubGroupController@trash')->name('categorySubGroup.trash');
Route::post('categorySubGroup/massTrash', 'CategorySubGroupController@massTrash')->name('categorySubGroup.massTrash');
Route::post('categorySubGroup/massDestroy', 'CategorySubGroupController@massDestroy')->name('categorySubGroup.massDestroy');
Route::delete('categorySubGroup/emptyTrash', 'CategorySubGroupController@emptyTrash')->name('categorySubGroup.emptyTrash');
Route::get('categorySubGroup/{categorySubGroup}/restore', 'CategorySubGroupController@restore')->name('categorySubGroup.restore');
Route::resource('categorySubGroup', 'CategorySubGroupController', ['except' => ['show']]);