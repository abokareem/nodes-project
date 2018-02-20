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

Route::post('money', 'UserBillController@putMoney');
Route::post('money/accept', 'UserBillController@acceptPutMoney');
Route::delete('money', 'UserBillController@withdrawalMoney');

Route::post('users', 'RegisterController@store');
Route::get('users/email/confirm/{token}', 'UserController@confirmEmail')->name('email.confirm.backend');
Route::post('users/auth', 'UserAuthController@login');
Route::post('users/auth/twofa', 'UserAuthController@twoFaLogin');

Route::get('currency', 'CurrencyController@index');
Route::get('currency/{currency}', 'CurrencyController@show');

Route::get('nodes', 'MasternodeController@index');
Route::get('nodes/{node}', 'MasternodeController@show');

Route::middleware(['auth:api', 'confirmEmail'])->group(function (Router $router) {

    $router->get('users', 'UserController@show');
    $router->post('users/twofa', 'UserController@enableTwoFa');
    $router->delete('users/twofa', 'UserController@disableTwoFa');
    $router->get('users/twofa', 'UserController@twoFaDataForActivate');
    $router->get('users/actions', 'UserController@getActions');
    $router->patch('users', 'UserController@update')->middleware('tfa');

    $router->post('shares/buy', 'ShareController@buy');
});

Route::middleware(['auth:api', 'confirmEmail', 'node'])->group(function (Router $router) {
    $router->post('nodes', 'MasternodeController@store');
});

Route::middleware(['auth:api', 'admin'])->group(function (Router $router) {

    $router->post('currency', 'CurrencyController@store');
    $router->patch('currency/{currency}', 'CurrencyController@update');
    $router->patch('nodes/{node}', 'MasternodeController@update');
});

Route::middleware('auth:api')->group(function (Router $router) {

    $router->get('users/email/resend', 'UserController@resendConfirmEmail');
});

Route::middleware('throttle:15')->group(function (Router $router) {

    $router->post('users/password', 'ForgotPasswordController@store');
    $router->patch('users/password', 'ResetPasswordController@update')->name('password.reset');
    $router->patch('users/twofa', 'UserController@resetTwoFa');
});

Route::middleware(['auth:api', 'leaveNode'])->group(function (Router $router) {

    $router->post('test', function () {
        echo 'hello';
    });
});
