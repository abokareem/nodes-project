<?php

namespace App\Listeners;

use App\Events\UpdatedUserEmail;

class SetStatusEmailUnconfirmed
{
    /**
     * Handle the event.
     *
     * @param  UpdatedUserEmail  $event
     * @return void
     */
    public function handle(UpdatedUserEmail $event)
    {
        $user = $event->user;
        $user->email_confirmed = false;
        $user->save();
    }
}
