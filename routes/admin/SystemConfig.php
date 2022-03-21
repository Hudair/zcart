<?php
	// system Configs
	Route::put('system/config/{node}/toggle', 'SystemConfigController@toggleConfig')->name('system.config.toggle')->middleware('ajax');

	Route::put('system/paymentMethod/{id}/toggle', 'SystemConfigController@togglePaymentMethod')->name('system.paymentMethod.toggle')->middleware('ajax');

	Route::put('system/updateConfig', 'SystemConfigController@update')->name('system.update')->middleware('ajax');

	Route::get('system/config', 'SystemConfigController@view')->name('system.config');
