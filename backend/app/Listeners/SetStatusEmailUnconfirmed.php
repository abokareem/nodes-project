<?php

namespace App\Listeners;

use App\Events\UpdatedUserEmail;
use App\User;

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

        User::find($user->id)->update([
            'email_confirmed' => false
        ]);
    }
}
