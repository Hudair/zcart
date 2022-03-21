<?php
	// Bulk upload routes
	Route::get('customer/upload/downloadTemplate', 'CustomerUploadController@downloadTemplate')->name('customer.downloadTemplate');
	Route::get('customer/upload', 'CustomerUploadController@showForm')->name('customer.bulk');
	Route::post('customer/upload', 'CustomerUploadController@upload')->name('customer.upload');
	Route::post('customer/import', 'CustomerUploadController@import')->name('customer.import');
	Route::post('customer/downloadFailedRows', 'CustomerUploadController@downloadFailedRows')->name('customer.downloadFailedRows');

	// Customer Routes
	Route::get('changePasswordForm/{customer}', 'CustomerController@ShowChangePasswordForm')->name('customer.changePassword');
	Route::put('updatePassword/{customer}', 'CustomerController@updatePassword')->name('customer.updatePassword');
	Route::post('customer/massTrash', 'CustomerController@massTrash')->name('customer.massTrash');
	Route::post('customer/massDestroy', 'CustomerController@massDestroy')->name('customer.massDestroy');
	Route::delete('customer/emptyTrash', 'CustomerController@emptyTrash')->name('customer.emptyTrash');
	Route::get('customer/{customer}/profile', 'CustomerController@profile')->name('customer.profile');
	Route::get('customer/{customer}/addresses', 'CustomerController@addresses')->name('customer.addresses');
	Route::delete('customer/{customer}/trash', 'CustomerController@trash')->name('customer.trash');
	Route::get('customer/{customer}/restore', 'CustomerController@restore')->name('customer.restore');
	Route::get('customer/getCustomers', 'CustomerController@getCustomers')->name('customer.getMore')->middleware('ajax');
	Route::resource('customer', 'CustomerController');