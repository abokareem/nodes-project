<?php

namespace App\Listeners;

use App\User;

class CheckTwoFa
{
    /**
     * Handle the event.
     *
     * @param  object $event
     * @return void
     */
    public function handle($event)
    {
        $user = User::findOrFail($event->userId);

        if ($user->two_fa) {
            if ($user->google2fa_secret) {

            }
        }

    }
}
