<?php
Route::get('attributeValue/create/{attribute?}', 'AttributeValueController@create')->name('attributeValue.create');
Route::delete('attributeValue/{attributeValue}/trash', 'AttributeValueController@trash')->name('attributeValue.trash');
Route::get('attributeValue/{attributeValue}/restore', 'AttributeValueController@restore')->name('attributeValue.restore');
Route::post('attributeValue/massTrash', 'AttributeValueController@massTrash')->name('attributeValue.massTrash')->middleware('demoCheck');
Route::post('attributeValue/massDestroy', 'AttributeValueController@massDestroy')->name('attributeValue.massDestroy')->middleware('demoCheck');
Route::delete('attributeValue/emptyTrash', 'AttributeValueController@emptyTrash')->name('attributeValue.emptyTrash');
Route::post('attributeValue/reorder', 'AttributeValueController@reorder')->name('attributeValue.reorder');
Route::resource('attributeValue', 'AttributeValueController', ['except' => ['index', 'create']]);
