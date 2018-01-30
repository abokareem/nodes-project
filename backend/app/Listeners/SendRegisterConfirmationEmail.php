<?php

namespace App\Listeners;


use App\EmailConfirmation;
use App\Mail\UserEmailConfirmation;
use Illuminate\Support\Facades\Mail;

class SendRegisterConfirmationEmail
{
    /**
     * Handle the event.
     *
     * @param  object $event
     * @return void
     */
    public function handle($event)
    {
        $token = md5(str_random(15));
        $user = $event->user;

        EmailConfirmation::create([
            'user_id' => $user->id,
            'token' => $token,
            'email_confirmed_tries' => $event->tries
        ]);

        Mail::to($user->email)->queue(new UserEmailConfirmation($user, $token));
    }
}
