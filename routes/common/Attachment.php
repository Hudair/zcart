<?php
	Route::get('attachment/{attachment}/download', 'AttachmentController@download')->name('attachment.download');

	Route::delete('attachment/{attachment}', 'AttachmentController@destroy')->name('attachment.delete');
