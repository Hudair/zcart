<?php
Route::get('search/customer', 'SearchController@findCustomer')->name('search.customer')->middleware('ajax');

Route::get('search/product', 'SearchController@findProduct')->name('search.product')->middleware('ajax');

Route::get('message/search', 'SearchController@findMessage')->name('message.search');

Route::get('search/merchant', 'SearchController@findMerchant')->name('search.merchant')->middleware('ajax');

Route::get('search/findProduct', 'SearchController@findProductForSelect')->name('search.findProduct')->middleware('ajax');

Route::get('search/findInventory', 'SearchController@findInventoryForSelect')->name('search.findInventory')->middleware('ajax');
