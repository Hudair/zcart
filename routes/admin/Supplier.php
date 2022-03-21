<?php
// Suppliers
Route::post('supplier/massTrash', 'SupplierController@massTrash')->name('supplier.massTrash')->middleware('demoCheck');
Route::post('supplier/massDestroy', 'SupplierController@massDestroy')->name('supplier.massDestroy')->middleware('demoCheck');
Route::delete('supplier/emptyTrash', 'SupplierController@emptyTrash')->name('supplier.emptyTrash');
Route::delete('supplier/{supplier}/trash', 'SupplierController@trash')->name('supplier.trash'); // supplier move to trash
Route::get('supplier/{supplier}/restore', 'SupplierController@restore')->name('supplier.restore');
Route::resource('supplier', 'SupplierController');