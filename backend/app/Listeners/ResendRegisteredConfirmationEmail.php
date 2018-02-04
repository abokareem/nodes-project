<?php

namespace App\Listeners;

use App\Mail\UserEmailConfirmation;
use Illuminate\Support\Facades\Mail;

class ResendRegisteredConfirmationEmail
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $confirmation = $event->emailConfirmation;

        $user = $confirmation->user;

        $token = md5($user->email . str_random(15));

        $confirmation->token = $token;
        $confirmation->email_confirmed_tries = $confirmation->email_confirmed_tries + 1;
        $confirmation->save();

        Mail::to($user->email)->queue(new UserEmailConfirmation($user, $token));
    }
}
