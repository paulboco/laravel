<?php

Route::group(['middleware' => 'web'], function () {
    Route::auth();

    Route::group(['middleware' => 'auth'], function () {
        Route::get('/home', 'HomeController@index');
    });

    Route::get('/', function () {
        return view('welcome');
    });
});
