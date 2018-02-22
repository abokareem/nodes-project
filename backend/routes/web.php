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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/email/confirm/{token}', function ($token) {
    return redirect(route('email.confirm.backend', [$token]));
})->name('email.confirm.frontend');

Route::get('password/reset/{token}')->name('reset.password');
