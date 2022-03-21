<?php
Route::get('chat/{shop}', 'ChatController@conversation')->name('chat.conversation')->middleware('ajax');
Route::post('chat', 'ChatController@save')->name('chat.start')->middleware('ajax');