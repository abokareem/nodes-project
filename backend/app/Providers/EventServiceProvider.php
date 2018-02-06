<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\UserRegistered' => [
            'App\Listeners\WriteLog',
            'App\Listeners\SendRegisterConfirmationEmail'
        ],
        'App\Events\ResendUserRegisteredEmail' => [
            'App\Listeners\WriteLog',
            'App\Listeners\ResendRegisteredConfirmationEmail'
        ],
        'App\Events\ForgottenPasswordRequested'  => [
            'App\Listeners\SendForgotPasswordEmail',
        ],
        'App\Events\ResetPassword'  => [
            'App\Listeners\WriteLog',
        ],
        'App\Events\Login'  => [
            'App\Listeners\WriteLog',
        ],
        'App\Events\ConfirmedEmail'  => [
            'App\Listeners\WriteLog',
        ],
        'App\Events\TwoFaEnable'  => [
            'App\Listeners\WriteLog',
        ],
        'App\Events\TwoFaDisable'  => [
            'App\Listeners\WriteLog',
        ],
        'App\Events\TwoFaReset'  => [
            'App\Listeners\WriteLog',
        ],
    ];


    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
