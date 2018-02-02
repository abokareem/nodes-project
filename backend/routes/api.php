<?php

use Illuminate\Routing\Router;
use Illuminate\Http\Request;

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

Route::post('users', 'RegisterController@store');

Route::get('users/email/confirm/{token}', 'UserController@confirmEmail')
    ->name('email.confirm.backend');

Route::middleware('auth:api')->group(function(Router $router) {
    //$router->get('users', 'UserController@show');
    $router->post('users/twofa/enabled','UserController@enableTwoFa');
});

Route::post('users/auth','UserAuthController@login')->middleware('tfa');

Route::post('users/auth/twofa','UserAuthController@twoFaLogin')->middleware('tfa');

Route::get('users', 'UserController@show')->middleware(['tfa']);