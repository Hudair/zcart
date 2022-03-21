<?php

// use Illuminate\Http\Request;

Route::group(['namespace' => 'Api'], function(){
	Route::get('sliders', 'HomeController@sliders');
	Route::get('banners', 'HomeController@banners');
	Route::get('category-grps', 'CategoryController@categoryGroup');
	Route::get('category-subgrps/{group?}', 'CategoryController@categorySubGroup');
	Route::get('categories/{sub_group?}', 'CategoryController@index');
	Route::get('countries', 'HomeController@countries');
	Route::get('states/{country}', 'HomeController@states');

	Route::get('blogs', 'BlogController@index');
	Route::get('blog/{slug}', 'BlogController@show');

	Route::get('page/{slug}', 'HomeController@page');
	Route::get('shops', 'HomeController@allShops');
	Route::get('shop/{slug}', 'HomeController@shop');
	Route::get('brand/{slug}', 'HomeController@brand');
	Route::get('packaging/{shop}', 'HomeController@packaging');
	Route::post('shipping/{shop}', 'HomeController@shipping');
	Route::get('paymentOptions/{shop}', 'HomeController@paymentOptions');
	Route::get('offers/{slug}', 'HomeController@offers');
	Route::get('listings/{list?}', 'ListingController@index');
	Route::get('listing/{slug}', 'ListingController@item');
	Route::post('variant/{slug}', 'ListingController@variant');
	Route::get('search/{term}', 'ListingController@search');
	Route::get('shop/{slug}/listings', 'ListingController@shop');
	Route::get('brand/{slug}/listings', 'ListingController@brand');
	Route::get('listing/category/{slug}', 'ListingController@category');
	Route::get('listing/category-subgrp/{slug}', 'ListingController@categorySubGroup');
	Route::get('listing/category-grp/{slug}', 'ListingController@categoryGroup');
	Route::post('listing/{item}/shipTo', 'ListingController@shipTo');
	Route::get('listing/{slug}/feedbacks', 'FeedbackController@show_item_feedbacks');
	Route::get('shop/{slug}/feedbacks', 'FeedbackController@show_shop_feedbacks');

	// CART
	Route::post('addToCart/{slug}', 'CartController@addToCart');
	Route::delete('cart/removeItem', 'CartController@remove');
	Route::get('carts', 'CartController@index');
	Route::get('cart/{cart}', 'CartController@show');
	Route::put('cart/{cart}/update', 'CartController@update');
	Route::post('cart/{cart}/shipTo', 'CartController@shipTo');
	Route::post('cart/{cart}/shipping', 'CartController@shipping');
	Route::post('cart/{cart}/checkout', 'CheckoutController@checkout');

	// Route::get('cart/{expressId?}', 'CartController@index')->name('cart.index');
	// Route::get('checkout/{slug}', 'CheckoutController@directCheckout');

	Route::group(['prefix' => 'auth'], function(){
		Route::post('register', 'AuthController@register');
		Route::post('login', 'AuthController@login');
		Route::post('logout', 'AuthController@logout')->middleware(['auth:api']);
		Route::post('forgot', 'AuthController@forgot');
    	Route::get('reset/{token}', 'AuthController@find');
		Route::post('reset', 'AuthController@reset');
    	Route::post('social/{provider}', 'AuthController@socialLogin');
    	// Route::get('social/{provider}', 'AuthController@socialLogin');
    	// Route::get('social/{provider}/callback', 'AuthController@handleSocialProviderCallback');
	});

	Route::group(['middleware' => 'auth:api'], function(){
		Route::get('dashboard', 'AccountController@index');
		Route::get('account/update', 'AccountController@edit');
		Route::put('account/update', 'AccountController@update');
		Route::put('password/update', 'AccountController@password_update');
		Route::get('addresses', 'AddressController@index');
		Route::get('address/create', 'AddressController@create');
		Route::post('address/store', 'AddressController@store');
		Route::get('address/{address}', 'AddressController@edit');
		Route::put('address/{address}', 'AddressController@update');
		Route::delete('address/{address}', 'AddressController@delete');
		Route::get('coupons', 'AccountController@coupons');
		Route::post('cart/{cart}/applyCoupon', 'CartController@validateCoupon');
		Route::get('wishlist', 'WishlistController@index');
		Route::get('wishlist/{slug}/add', 'WishlistController@add');
		Route::delete('wishlist/{wishlist}/remove', 'WishlistController@remove');
		Route::get('orders', 'OrderController@index');
		Route::get('order/{order}', 'OrderController@show');
		Route::get('order/{order}/conversation', 'OrderController@conversation');
		Route::post('order/{order}/conversation', 'OrderController@save_conversation');
		Route::get('order/{order}/track', 'OrderController@track');
		Route::get('conversations', 'ConversationController@conversations');
		Route::get('shop/{shop}/contact', 'ConversationController@conversation');
		Route::post('shop/{shop}/contact', 'ConversationController@save_conversation');
		Route::post('shop/{order}/feedback', 'FeedbackController@save_shop_feedbacks');
		Route::post('order/{order}/feedback', 'FeedbackController@save_product_feedbacks');
		Route::post('order/{order}/goodsReceived', 'OrderController@goods_received');

		Route::get('disputes', 'DisputeController@index');
		Route::get('order/{order}/dispute', 'DisputeController@create');
		Route::post('order/{order}/dispute', 'DisputeController@store');
		Route::get('dispute/{dispute}', 'DisputeController@show');
		Route::get('dispute/{dispute}/response', 'DisputeController@response_form');
		Route::post('dispute/{dispute}/response', 'DisputeController@response');
		Route::post('dispute/{dispute}/appeal', 'DisputeController@appeal');
		Route::put('dispute/{dispute}/solved', 'DisputeController@mark_as_solved');

		Route::get('attachment/{attachment}/download', 'AttachmentController@download');
	});
});