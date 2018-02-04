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
Route::post('users/auth', 'UserAuthController@login');
Route::post('users/auth/twofa', 'UserAuthController@twoFaLogin');

Route::middleware(['auth:api', 'confirmEmail'])->group(function (Router $router) {
    $router->get('users', 'UserController@show');
    $router->post('users/twofa', 'UserController@enableTwoFa');
    $router->delete('users/twofa', 'UserController@disableTwoFa');
    $router->get('users/twofa', 'UserController@twoFaDataForActivate');
});

Route::middleware('auth:api')->group(function (Router $router) {
    $router->get('users/email/resend', 'UserController@resendConfirmEmail');
});

Route::middleware('throttle:15')->group(function (Router $router) {
    $router->post('users/password', 'ForgotPasswordController@store');
    $router->patch('users/password', 'ResetPasswordController@update')
        ->name('password.reset');
    $router->patch('users/twofa', 'UserController@resetTwoFa');
});
