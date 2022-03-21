<?php
	// Support chats
    Route::get('chat', 'ChatController@index')->name('chat_conversation.index');
    Route::get('chat/{chat}', 'ChatController@show')->name('chat_conversation.show');
    Route::post('chat/{chat}/reply', 'ChatController@reply')->name('chat_conversation.reply');
	// Route::delete('chat/{chat}/trash', 'ChatController@trash')->name('chat_conversation.trash'); // Chat move to trash
	// Route::post('chat/{chat}/restore', 'ChatController@restore')->name('chat_conversation.restore');
    // Route::delete('chat/{chat}/destroy', 'ChatController@destroy')->name('chat_conversation.destroy');
