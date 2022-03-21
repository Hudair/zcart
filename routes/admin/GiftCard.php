<?php
// giftCards
Route::post('giftCard/massTrash', 'GiftCardController@massTrash')->name('giftCard.massTrash')->middleware('demoCheck');
Route::post('giftCard/massDestroy', 'GiftCardController@massDestroy')->name('giftCard.massDestroy')->middleware('demoCheck');
Route::delete('giftCard/emptyTrash', 'GiftCardController@emptyTrash')->name('giftCard.emptyTrash');
Route::delete('giftCard/{giftCard}/trash', 'GiftCardController@trash')->name('giftCard.trash'); // giftCard move to trash
Route::get('giftCard/{giftCard}/restore', 'GiftCardController@restore')->name('giftCard.restore');
Route::resource('giftCard', 'GiftCardController');
