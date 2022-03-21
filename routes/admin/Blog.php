<?php
Route::post('blog/massTrash', 'BlogController@massTrash')->name('blog.massTrash')->middleware('demoCheck');
Route::post('blog/massDestroy', 'BlogController@massDestroy')->name('blog.massDestroy')->middleware('demoCheck');
Route::delete('blog/emptyTrash', 'BlogController@emptyTrash')->name('blog.emptyTrash');
Route::delete('blog/{blog}/trash', 'BlogController@trash')->name('blog.trash'); // Blog post move to trash
Route::get('blog/{blog}/restore', 'BlogController@restore')->name('blog.restore');
Route::resource('blog', 'BlogController', ['except' => ['show']]);