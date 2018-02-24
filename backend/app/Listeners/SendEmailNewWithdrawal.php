<?php

namespace App\Listeners;

use App\Events\NewWithdrawal;
use App\Mail\NewWithdrawal as Email;
use Illuminate\Support\Facades\Mail;

class SendEmailNewWithdrawal
{
    /**
     * Handle the event.
     *
     * @param  NewWithdrawal $event
     * @return void
     */
    public function handle(NewWithdrawal $event)
    {
        $node = $event->withdrawal->node;
        $investors = $node->investments()->whereNotIn('user_id',
            [$event->withdrawal->user_id])->get();

        foreach ($investors as $investor) {
            $user = $investor->user;
            if ($user->subscribe) {
                Mail::to($user->email)->queue(new Email($event->withdrawal, $node->currency));
            }
        }
    }
}
