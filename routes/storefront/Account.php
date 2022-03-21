<?php
	Route::get('my/{tab?}', 'AccountController@index')->name('account');
	Route::get('wishlist/{item}', 'WishlistController@add')->name('wishlist.add')->middleware(['ajax']);
	Route::delete('wishlist/{wishlist}', 'WishlistController@remove')->name('wishlist.remove');

	Route::put('my/password/update', 'AccountController@password_update')->name('my.password.update');
	Route::put('my/account/update', 'AccountController@update')->name('account.update');

	// Avatar
	Route::post('my/avatar/save', 'AccountController@avatar')->name('my.avatar.save');
	Route::delete('my/avatar/remove', 'AccountController@delete_avatar')->name('my.avatar.remove');

	// Address
	Route::get('my/address/create', 'AccountController@create_address')->name('my.address.create');
	Route::post('my/address/save', 'AccountController@save_address')->name('my.address.save');
	Route::get('my/address/{address}', 'AccountController@address_edit')->name('my.address.edit');
	Route::put('my/address/{address}/update', 'AccountController@address_update')->name('my.address.update');
	Route::get('my/address/{address}/delete', 'AccountController@address_delete')->name('my.address.delete');
