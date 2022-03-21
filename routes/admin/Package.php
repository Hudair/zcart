<?php
	// Package
    Route::get('packages', 'PackagesController@index')->name('packages');

	Route::get('package/upload', 'PackagesController@upload')->name('package.upload');

	Route::post('package/upload', 'PackagesController@save')->name('package.save');

	Route::get('package/{package}/initiate', 'PackagesController@initiate')->name('package.initiate');

	Route::post('package/{package}/install', 'PackagesController@install')->name('package.install');

	Route::post('package/{package}/uninstall', 'PackagesController@uninstall')->name('package.uninstall');

	Route::post('package/{package}/update', 'PackagesController@updateConfig')->name('package.config.update');

    Route::put('package/{package}/switch', 'PackagesController@activation')->name('package.switch')->middleware('ajax');

	Route::put('package/toggle/{option}', 'PackagesController@toggleConfig')->name('package.config.toggle')->middleware('ajax');
