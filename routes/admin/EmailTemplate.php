<?php
Route::post('emailTemplate/massTrash', 'EmailTemplateController@massTrash')->name('emailTemplate.massTrash')->middleware('demoCheck');
Route::post('emailTemplate/massDestroy', 'EmailTemplateController@massDestroy')->name('emailTemplate.massDestroy')->middleware('demoCheck');
Route::delete('emailTemplate/emptyTrash', 'EmailTemplateController@emptyTrash')->name('emailTemplate.emptyTrash');
Route::delete('emailTemplate/{emailTemplate}/trash', 'EmailTemplateController@trash')->name('emailTemplate.trash');
Route::get('emailTemplate/{emailTemplate}/restore', 'EmailTemplateController@restore')->name('emailTemplate.restore');
Route::resource('emailTemplate', 'EmailTemplateController');