<?php

/**
 * Macro for uLogin widget.
 */
HTML::macro('ulogin', function($options = array())
{
    Session::set('ulogin_error_redirect', Route::getCurrentRoute()->uri());

    $config  = Config::get('ulogin-laravel::config');
    $options = array_merge($config, $options);

    foreach(['fields', 'providers', 'hidden'] as $key)
    {
        $options[$key] = implode(',', $options[$key]);
    }

    $widgetKeys = ['display', 'fields', 'providers', 'hidden', 'redirect_uri'];
    $widgetOptions = array_only($options, $widgetKeys);
    $widgetOptions['redirect_uri'] = url($widgetOptions['redirect_uri']);
    $widgetOptions = str_replace('%2C', ',', http_build_query($widgetOptions, '', ';'));

    $viewData = [
        'display' => $options['display'],
        'params'  => $widgetOptions,
        'button'  => $options['window_button']
    ];

    return View::make($options['views']['widget'], $viewData);
});