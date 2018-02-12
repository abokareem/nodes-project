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
        $user = $event->user;
        $token = md5($user->email . str_random(15));

        $confirmation = EmailConfirmation::where('user_id', $user->id)->first();

        if ($confirmation) {

            $confirmation->update([
                'token' => $token,
                'email_confirmed_tries' => $event->tries
            ]);

        } else {

            EmailConfirmation::create([
                'user_id' => $user->id,
                'token' => $token,
                'email_confirmed_tries' => $event->tries
            ]);
        }


        Mail::to($user->email)->queue(new UserEmailConfirmation($user, $token));
    }
}
