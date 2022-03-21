<?php
	Route::post('order/{cart}', 'OrderController@create')->name('order.create');
	Route::get('paymentFailed/{order}', 'OrderController@paymentFailed')->name('payment.failed');
	Route::get('paymentSuccess/{gateway}/{order}', 'OrderController@paymentGatewaySuccessResponse')->name('payment.success');

	Route::middleware(['auth:customer'])->group(function () {
		Route::get('order/{order}', 'OrderController@detail')->name('order.detail');
		Route::get('order/invoice/{order}', 'OrderController@invoice')->name('order.invoice');
		Route::get('order/track/{order}', 'OrderController@track')->name('order.track');
		Route::put('order/goodsReceived/{order}', 'OrderController@goods_received')->name('goods.received');
		Route::get('order/again/{order}', 'OrderController@again')->name('order.again');

		// Order cancel
		Route::get('order/cancel/{order}/{action?}', 'OrderCancelController@showForm')->name('cancellation.form');
		Route::put('order/cancel/{order}', 'OrderCancelController@cancel')->name('order.cancel');
		Route::post('order/cancel/{order}', 'OrderCancelController@saveCancelRequest')->name('order.submitCancelRequest');

		// Conversations
		Route::post('order/conversation/{order}', 'ConversationController@order_conversation')->name('order.conversation');

		// Disputes
		Route::get('order/dispute/{order}', 'DisputeController@show_dispute_form')->name('dispute.open');
		Route::post('order/dispute/{order}', 'DisputeController@open_dispute')->name('dispute.save');
		Route::post('dispute/{dispute}', 'DisputeController@response')->name('dispute.response');
		Route::post('dispute/{dispute}/appeal', 'DisputeController@appeal')->name('dispute.appeal');
		Route::post('dispute/{dispute}/markAsSolved', 'DisputeController@markAsSolved')->name('dispute.markAsSolved');

		// Refunds
		// Route::post('order/refund/{order}', 'DisputeController@refund_request')->name('refund.request');
	});