<?php

namespace App\Listeners;

use App\Exceptions\TwoFaActive;
use App\Exceptions\TwoFaSecretNotExists;
use App\User;

class CheckTwoFa
{
    /**
     * @param $event
     * @throws TwoFaActive
     * @throws TwoFaSecretNotExists
     */
    public function handle($event)
    {
        dd($event);
        $user = User::findOrFail($event->userId);

        $this->twoFaCheck($user);

    }

    protected function twoFaCheck(User $user)
    {
        if ($user->two_fa) {

            if (!$user->google2fa_secret) {

                throw new TwoFaSecretNotExists();
            }

            throw new TwoFaActive($user->id);
        }
    }
}
