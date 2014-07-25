<?php

Route::filter('ulogin-guest', function()
{
    if (Auth::check())
    {
        $uri = Config::get('ulogin-laravel::config.redirect_after_login');

        return Redirect::to($uri);
    }
});