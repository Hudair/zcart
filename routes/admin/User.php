<?php
Route::get('changePasswordForm/{user}', 'UserController@ShowChangePasswordForm')->name('user.changePassword');
Route::put('updatePassword/{user}', 'UserController@updatePassword')->name('user.updatePassword');
Route::post('user/massTrash', 'UserController@massTrash')->name('user.massTrash')->middleware('demoCheck');
Route::post('user/massDestroy', 'UserController@massDestroy')->name('user.massDestroy')->middleware('demoCheck');
Route::delete('user/emptyTrash', 'UserController@emptyTrash')->name('user.emptyTrash');
Route::delete('user/{user}/trash', 'UserController@trash')->name('user.trash');
Route::get('user/{user}/restore', 'UserController@restore')->name('user.restore');
Route::resource('user', 'UserController');