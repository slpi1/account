<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */
Route::group([

    'middleware' => 'api',
    'prefix'     => 'auth',

], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('profile', 'IndexController@profile');
    Route::get('password', 'PasswordController@password');

    Route::get('account', 'AccountController@index');
    Route::put('account', 'AccountController@create');
    Route::delete('account/{id}', 'AccountController@delete');

    Route::get('site', 'SiteController@index');
    Route::put('site', 'SiteController@create');
});
