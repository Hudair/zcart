<?php
Route::group(['prefix' => 'incevio'], function()
{
    // Check different type system information
    Route::get('check/{option?}', 'Incevio@check')->name('incevio.check');

    // New version upgrade
    Route::get('upgrade/{option?}', 'Incevio@upgrade')->name('incevio.upgrade');

    // Run Artisan command
    Route::get('command/{option?}', 'Incevio@command')->name('incevio.command');

    // Clear config. cache etc
    Route::get('clear/{all?}', 'Incevio@clear')->name('incevio.clear');

    // Clear scout. cache etc
    // Route::get('scout/{all?}', 'Incevio@scout')->name('incevio.scout');
});