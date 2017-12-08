<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/** @var \Illuminate\Routing\Router $router */
$router->get('/', function () {
    return view('welcome');
});

// todo: admin permissions
$router->group(['prefix' => 'admin'], function ($router) {
    /** @var \Illuminate\Routing\Router $router */
    $router->resource(\Localization::transRoute('routes.countries'), 'CountryController', ['except' => ['show']]);
    $router->resource(\Localization::transRoute('routes.currencies'), 'CurrencyController', ['except' => ['show']]);
    $router->resource(\Localization::transRoute('routes.languages'), 'LanguageController', ['except' => ['show']]);
    $router->resource(\Localization::transRoute('routes.locales'), 'LocaleController', ['except' => ['show']]);
});

$router->get('{all}', function () {
    app()->abort(404);
})->where('all', '.*');
