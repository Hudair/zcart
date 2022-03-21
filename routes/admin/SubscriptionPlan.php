<?php
// SubscriptionPlans
Route::middleware(['subscriptionEnabled'])->group(function ()
{
	Route::delete('subscriptionPlan/{subscriptionPlan}/trash', 'SubscriptionPlanController@trash')->name('subscriptionPlan.trash');
	Route::get('subscriptionPlan/{subscriptionPlan}/restore', 'SubscriptionPlanController@restore')->name('subscriptionPlan.restore');
	Route::post('subscriptionPlan/massTrash', 'SubscriptionPlanController@massTrash')->name('subscriptionPlan.massTrash')->middleware('demoCheck');
	Route::post('subscriptionPlan/massDestroy', 'SubscriptionPlanController@massDestroy')->name('subscriptionPlan.massDestroy')->middleware('demoCheck');
	Route::delete('subscriptionPlan/emptyTrash', 'SubscriptionPlanController@emptyTrash')->name('subscriptionPlan.emptyTrash');
	Route::post('subscriptionPlan/reorder', 'SubscriptionPlanController@reorder')->name('subscriptionPlan.reorder');
	Route::resource('subscriptionPlan', 'SubscriptionPlanController');
});