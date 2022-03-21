<?php
Route::get('order/{order}/invoice', 'OrderController@invoice')->name('order.invoice');
// Route::post('order/massTrash', 'OrderController@massTrash')->name('order.massTrash')->middleware('demoCheck');
// Route::post('order/massDestroy', 'OrderController@massDestroy')->name('order.massDestroy')->middleware('demoCheck');
Route::delete('order/emptyTrash', 'OrderController@emptyTrash')->name('order.emptyTrash');
Route::get('order/{order}/adminNote', 'OrderController@adminNote')->name('order.adminNote');
Route::put('order/{order}/adminNote', 'OrderController@saveAdminNote')->name('order.saveAdminNote');
Route::delete('order/{order}/archive', 'OrderController@archive')->name('order.archive'); // order move to trash
#show
Route::get('/{order}/details', 'OrderController@show')->name('details'); // order Details

// Cancellation routes
Route::get('order/{order}/cancel', 'OrderCancellationController@create')->name('cancellation.create');
Route::put('order/{order}/cancel', 'OrderCancellationController@cancel')->name('order.cancel');
Route::get('cancellation', 'OrderCancellationController@index')->name('order.cancellation');
Route::put('cancellation/{order}/{action}', 'OrderCancellationController@handleCancellationRequest')->name('cancellation.handle');

Route::get('order/{order}/restore', 'OrderController@restore')->name('order.restore');
Route::get('order/searchCutomer', 'OrderController@searchCutomer')->name('order.searchCutomer');
Route::get('order/{order}/fulfill', 'OrderController@fulfillment')->name('order.fulfillment');
Route::put('order/{order}/fulfill', 'OrderController@fulfill')->name('order.fulfill');
Route::put('order/{order}/updateOrderStatus', 'OrderController@updateOrderStatus')->name('order.updateOrderStatus');
Route::put('order/{order}/togglePaymentStatus', 'OrderController@togglePaymentStatus')->name('order.togglePaymentStatus');
Route::resource('order', 'OrderController', ['except' => ['update']]);