<?php
	// Verification
	Route::get('verify', 'ConfigController@verify')->name('verify');

	Route::post('verify', 'ConfigController@saveVerificationData')->name('verify.submit');