<?php
// warehouses
Route::post('warehouse/massTrash', 'WarehouseController@massTrash')->name('warehouse.massTrash')->middleware('demoCheck');
Route::post('warehouse/massDestroy', 'WarehouseController@massDestroy')->name('warehouse.massDestroy')->middleware('demoCheck');
Route::delete('warehouse/emptyTrash', 'WarehouseController@emptyTrash')->name('warehouse.emptyTrash');
Route::delete('warehouse/{warehouse}/trash', 'WarehouseController@trash')->name('warehouse.trash'); // warehouse move to trash
Route::get('warehouse/{warehouse}/restore', 'WarehouseController@restore')->name('warehouse.restore');
Route::resource('warehouse', 'WarehouseController');
