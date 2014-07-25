<?php

return [
    // User model to bind
    'user_model' => 'User',

    // Database table name for uLogin profiles storage
    'db_table' => 'ulogin_profiles',

    // Fields that uLogin service will retrieve
    'fields'  => [
        'email',
        'first_name',
        'last_name',
        'nickname',
        'photo',
        'photo_big',
        //'bdate',
        //'sex',
        'country',
        'city'
    ],

    // Visible social providers
    'providers' => [
        'facebook',
        'vkontakte',
        'twitter',
        'googleplus',
        'odnoklassniki',
        'mailru'
    ],

    // Hidden social providers
    'hidden' => ['other'],

    // Default uLogin widget appearance
    'display' => 'panel',

    // uLogin widget available styles
    'displays' => ['small', 'panel', 'window'],

    // Button that will be clicked for window to appear
    'window_button' => '<img src="https://ulogin.ru/img/button.png" alt="">',

    // Auth URI
    'redirect_uri' => '/ulogin',

    // Where to go after successful login
    'redirect_after_login' => '/',

    // Widget rendering options
    'views' => [
        'widget' => 'ulogin-laravel::widget',
        'errors' => 'ulogin-laravel::errors'
    ],
];