<?php
// coupons
Route::post('coupon/massTrash', 'CouponController@massTrash')->name('coupon.massTrash')->middleware('demoCheck');
Route::post('coupon/massDestroy', 'CouponController@massDestroy')->name('coupon.massDestroy')->middleware('demoCheck');
Route::delete('coupon/emptyTrash', 'CouponController@emptyTrash')->name('coupon.emptyTrash');
Route::delete('coupon/{coupon}/trash', 'CouponController@trash')->name('coupon.trash'); // coupon move to trash
Route::get('coupon/{coupon}/restore', 'CouponController@restore')->name('coupon.restore');
Route::resource('coupon', 'CouponController');
