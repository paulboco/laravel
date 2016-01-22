<?php

DB::enableQueryLog();

Route::group(['middleware' => 'web'], function () {
    Route::auth();

    Route::get('/property', 'PropertyController@index');

    Route::get('/{foo?}', 'DashboardController@index');
});
