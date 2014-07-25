<?php

Route::group(['before' => 'ulogin-guest'], function()
{
    $uloginUri  = Config::get('ulogin-laravel::config.redirect_uri');
    $routeName  = 'ulogin-laravel.ulogin';
    $controller = 'Franzose\UloginLaravel\Controllers\AuthController';

    Route::get($uloginUri, [
        'as'   => $routeName,
        'uses' => $controller . '@getLogin'
    ]);

    Route::post($uloginUri, [
        'as'   => $routeName,
        'uses' => $controller . '@postLogin'
    ]);
});