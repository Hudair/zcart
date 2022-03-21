<?php
    //Metrics / Key Performance Indicators...
    Route::get('report/kpi', 'PerformanceIndicatorsController@all')->name('kpi');
    Route::get('report/kpi/revenue', 'PerformanceIndicatorsController@revenue')->name('kpi.revenue');
    Route::get('report/kpi/plans', 'PerformanceIndicatorsController@subscribers')->name('kpi.plans');
    Route::get('report/kpi/trialing', 'PerformanceIndicatorsController@trialUsers')->name('kpi.trialing');
    #Sales report
    #Order Wise Report
    Route::get('report/sales/orders', 'SalesReportController@orders')->name('sales.orders');
    Route::get('report/sales/getMore', 'SalesReportController@getMoreOrder')->name('sales.getMore')->middleware('ajax');
    Route::get('report/sales/getMoreForChart', 'SalesReportController@getMoreForChart')->name('sales.getMoreForChart')->middleware('ajax');
    ##Paymnet Wise Report
    Route::get('report/sales/payments', 'SalesReportController@payments')->name('sales.payments');
    Route::get('report/sales/payments/getMethod', 'SalesReportController@getMoreByMethod')->name('sales.payments.getMethod');
    Route::get('report/sales/payments/getStatus', 'SalesReportController@getMoreByStatus')->name('sales.payments.getStatus');
    Route::get('report/sales/payments/getMoreForChart', 'SalesReportController@getMorePaymentForChart')->name('sales.payments.getMoreForChart');

    ##Product Wise Report
    Route::get('report/sales/products', 'SalesReportController@products')->name('sales.products');
    Route::get('report/sales/products/getMore', 'SalesReportController@productsSearch')->name('sales.products.getMore');