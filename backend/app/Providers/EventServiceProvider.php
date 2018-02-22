<?php

namespace App\Providers;

use App\Observers\UserObserver;
use App\User;
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
        'App\Events\ForgottenPasswordRequested' => [
            'App\Listeners\SendForgotPasswordEmail',
        ],
        'App\Events\ResetPassword' => [
            'App\Listeners\WriteLog',
        ],
        'App\Events\Login' => [
            'App\Listeners\WriteLog',
        ],
        'App\Events\ConfirmedEmail' => [
            'App\Listeners\WriteLog',
        ],
        'App\Events\TwoFaEnable' => [
            'App\Listeners\WriteLog',
        ],
        'App\Events\TwoFaDisable' => [
            'App\Listeners\WriteLog',
        ],

        'App\Events\TwoFaReset' => [
            'App\Listeners\WriteLog',
        ],

        'App\Events\UpdatedUserEmail' => [
            'App\Listeners\SendRegisterConfirmationEmail',
            'App\Listeners\SetStatusEmailUnconfirmed'
        ],

        'App\Events\UpdatedUserProfile' => [
            'App\Listeners\WriteLog'
        ],

        'App\Events\BoughtShares' => [
            'App\Listeners\WriteLog'
        ],

        'App\Events\MasternodeCreated' => [
            'App\Listeners\WriteLog'
        ],

        'App\Events\AcceptedPutMoney' => [
            'App\Listeners\WriteLog'
        ],

        'App\Events\MoneyWithdrawn' => [
            'App\Listeners\WriteLog'
        ],
        'App\Events\AcceptedLeaveFromNode' => [
            'App\Listeners\WriteLog'
        ],
        'App\Events\CreatedWithdrawal' => [
            'App\Listeners\WriteLog',
        ],
        'App\Events\DeclinedWithdrawal' => [
            'App\Listeners\WriteLog'
        ],
    ];

    protected $observers = [
        User::class => [
            UserObserver::class,
        ]
    ];


    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $this->registerObservers();
    }

    /**
     * Register observers for Eloquent models.
     *
     * @return void
     */
    protected function registerObservers()
    {
        foreach ($this->observers as $model => $observers) {
            foreach ($observers as $observer) {
                $model::observe($observer);
            }
        }
    }

}
