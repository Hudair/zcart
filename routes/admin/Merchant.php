<?php
Route::get('changePasswordForm/{merchant}', 'MerchantController@ShowChangePasswordForm')->name('merchant.changePassword');
Route::put('updatePassword/{merchant}', 'MerchantController@updatePassword')->name('merchant.updatePassword');
Route::resource('merchant', 'MerchantController', ['except' => ['delete']]);