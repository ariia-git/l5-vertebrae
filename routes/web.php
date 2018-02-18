<?php

/** @var \Illuminate\Routing\Router $router */
$router->get('/', 'DashboardController@index');

// Authentication Routes...
$this->get(\Localization::transRoute('routes.login'), 'Auth\LoginController@showLoginForm')->name('login');
$this->post(\Localization::transRoute('routes.login'), 'Auth\LoginController@login');
$this->post(\Localization::transRoute('routes.logout'), 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
$this->get(\Localization::transRoute('routes.register'), 'Auth\RegisterController@showRegistrationForm')->name('register');
$this->post(\Localization::transRoute('routes.register'), 'Auth\RegisterController@register');

// Password Reset Routes...
$this->get(\Localization::transRoute('routes.password.reset'), 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
$this->post(\Localization::transRoute('routes.password.email'), 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
$this->get(\Localization::transRoute('routes.password.reset') . '/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
$this->post(\Localization::transRoute('routes.password.reset'), 'Auth\ResetPasswordController@reset');

// Admin routes
$router->group(['prefix' => \Localization::transRoute('routes.admin'), 'middleware' => 'permission:admin'], function ($router) {
    /** @var \Illuminate\Routing\Router $router */
    $router->resource(\Localization::transRoute('routes.countries'), 'CountryController', ['except' => ['show']]);
    $router->resource(\Localization::transRoute('routes.currencies'), 'CurrencyController', ['except' => ['show']]);
    $router->resource(\Localization::transRoute('routes.languages'), 'LanguageController', ['except' => ['show']]);
    $router->resource(\Localization::transRoute('routes.locales'), 'LocaleController', ['except' => ['show']]);
});

$router->get('{all}', function () {
    app()->abort(404);
})->where('all', '.*');
