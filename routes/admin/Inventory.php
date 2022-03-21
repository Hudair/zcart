<?php
// Bulk upload routes
Route::get('inventory/upload/downloadTemplate', 'InventoryUploadController@downloadTemplate')->name('inventory.downloadTemplate');
Route::get('inventory/upload', 'InventoryUploadController@showForm')->name('inventory.bulk');
Route::post('inventory/upload', 'InventoryUploadController@upload')->name('inventory.upload');
Route::post('inventory/import', 'InventoryUploadController@import')->name('inventory.import');
Route::post('inventory/downloadFailedRows', 'InventoryUploadController@downloadFailedRows')->name('inventory.downloadFailedRows');

// Mass Actions
Route::post('inventory/massTrash', 'InventoryController@massTrash')->name('inventory.massTrash')->middleware('demoCheck');
Route::post('inventory/massDestroy', 'InventoryController@massDestroy')->name('inventory.massDestroy')->middleware('demoCheck');
Route::delete('inventory/emptyTrash', 'InventoryController@emptyTrash')->name('inventory.emptyTrash');

// inventories
Route::delete('inventory/{inventory}/trash', 'InventoryController@trash')->name('inventory.trash'); // inventory move to trash
Route::get('inventory/{inventory}/restore', 'InventoryController@restore')->name('inventory.restore');
Route::get('inventory/setVariant/{product}', 'InventoryController@setVariant')->name('inventory.setVariant');
Route::get('inventory/add/{product}', 'InventoryController@add')->name('inventory.add');
Route::get('inventory/addWithVariant/{product}', 'InventoryController@addWithVariant')->name('inventory.addWithVariant');
Route::post('inventory/storeWithVariant', 'InventoryController@storeWithVariant')->name('inventory.storeWithVariant');
Route::post('inventory/store', 'InventoryController@store')->name('inventory.store')->middleware('ajax');
Route::post('inventory/{inventory}/update', 'InventoryController@update')->name('inventory.update')->middleware('ajax');
Route::get('inventory/{inventory}/editQtt', 'InventoryController@editQtt')->name('inventory.editQtt');
Route::put('inventory/{inventory}/updateQtt', 'InventoryController@updateQtt')->name('inventory.updateQtt');
Route::get('inventory/{status}/getInventory', 'InventoryController@getInventory')->name('inventory.getMore')->middleware('ajax');
Route::resource('inventory', 'InventoryController', ['except' =>['create', 'store', 'update']]);
