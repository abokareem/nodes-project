<?php

namespace App\Listeners;

use App\Events\ForgottenPasswordRequested;
use App\Mail\ForgotPasswordEmail;
use Illuminate\Support\Facades\Mail;

class SendForgotPasswordEmail
{
    /**
     * Handle the event.
     *
     * @param ForgottenPasswordRequested $event
     *
     * @return void
     */
    public function handle(ForgottenPasswordRequested $event)
    {
        Mail::to($event->user->email)->queue(new ForgotPasswordEmail($event->user, $event->token));
    }
}

