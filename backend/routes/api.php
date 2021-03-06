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

//Route::post('money/accept', 'UserBillController@acceptPutMoney');

Route::post('users', 'RegisterController@store');
Route::get('users/email/confirm/{token}', 'UserController@confirmEmail')->name('email.confirm.backend');
Route::post('users/auth', 'UserAuthController@login');
Route::post('users/auth/twofa', 'UserAuthController@twoFaLogin');

Route::post('contact', 'SystemController@contactUs');

Route::get('currency', 'CurrencyController@index');
Route::get('currency/{currency}', 'CurrencyController@show');

Route::get('nodes/{node}', 'MasternodeController@show')->name('show.node');

Route::middleware('tryAuth:api')->group(function (Router $router) {
    $router->get('nodes', 'MasternodeController@index');
});

Route::middleware(['auth:api', 'confirmEmail'])->group(function (Router $router) {

    $router->get('users', 'UserController@show');
    $router->post('users/twofa', 'UserController@enableTwoFa');
    $router->delete('users/twofa', 'UserController@disableTwoFa');
    $router->get('users/twofa', 'UserController@twoFaDataForActivate');
    $router->post('users/twofa/auth', 'UserAuthController@twoFaAuthCode');
    $router->get('users/actions', 'UserController@getActions');
    $router->get('users/nodes', 'UserController@getNodes');
    $router->get('users/transactions', 'UserController@getTransactions');
    $router->get('users/withdrawals', 'UserController@getWithdrawals');

    $router->post('shares/buy', 'ShareController@buy');

    $router->delete('withdrawals/decline/{withdrawal}', 'WithdrawalController@decline');
    $router->post('withdrawals/buy/{withdrawal}', 'WithdrawalController@buy');

    $router->get('money/{currency}', 'UserBillController@getBill');
    $router->post('money', 'UserBillController@store');
    $router->delete('money', 'UserBillController@withdrawalMoney');
    $router->patch('money/approve/{withdrawal}', 'UserBillController@approve');
    $router->delete('money/decline/{withdrawal}', 'UserBillController@decline');

    $router->get('nodes/{node}/withdrawals', 'MasternodeController@getNodeWithdrawals');
});

Route::middleware(['auth:api', 'confirmEmail', 'node'])->group(function (Router $router) {
    $router->post('nodes', 'MasternodeController@store');
});
Route::middleware(['auth:api', 'admin'])->group(function (Router $router) {

    $router->post('currency', 'CurrencyController@store');
    $router->patch('currency/{currency}', 'CurrencyController@update');
    $router->patch('nodes/{node}', 'MasternodeController@update');

    $router->patch('withdrawals/approve/{withdrawal}', 'WithdrawalController@approve');

    $router->get('bills', 'Admin\UserBillController@index');
    $router->patch('bills/{bill}', 'Admin\UserBillController@update');
    $router->get('bills/{bill}', 'UserBillController@show');

    $router->patch('/shares/{share}', 'ShareController@update');
    $router->post('/shares/{share}', 'ShareController@store');

    $router->get('/admin/users', 'Admin\UserController@index');
    $router->get('/admin/users/{user}', 'Admin\UserController@show');
    $router->patch('/admin/users/{user}', 'Admin\UserController@update');
    $router->delete('/admin/users/{user}', 'Admin\UserController@destroy');
    $router->put('/admin/users/{user}', 'Admin\UserController@restore');

    $router->get('/admin/commissions', 'SystemController@getCommissions');
    $router->patch('/admin/commissions/{commission}', 'SystemController@updateCommissions');

    $router->post('/admin/nodes/{node}/profits', 'Admin\NodeController@setProfit');
    $router->get('/admin/nodes', 'Admin\NodeController@index');
    $router->get('/admin/withdrawals', 'Admin\NodeController@getWithdrawals');
    $router->get('/admin/money/withdrawals','UserBillController@getWithdrawals');

    $router->post('/systems/wallets', 'SystemController@loadWallets');

    $router->get('/admin/currency', 'Admin\CurrencyController@index');
});

Route::middleware('auth:api')->group(function (Router $router) {
    $router->patch('users', 'UserController@update')->middleware('tfa');
    $router->get('users/email/resend', 'UserController@resendConfirmEmail');
});

Route::middleware('throttle:15')->group(function (Router $router) {

    $router->post('users/password', 'ForgotPasswordController@store');
    $router->patch('users/password', 'ResetPasswordController@update')->name('password.reset');
    $router->patch('users/twofa', 'UserController@resetTwoFa');
});

Route::middleware(['auth:api', 'leaveNode'])->group(function (Router $router) {
    $router->post('withdrawals', 'WithdrawalController@store');
});
