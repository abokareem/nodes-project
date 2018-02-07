<?php

namespace App\Observers;

use App\Events\UpdatedUserEmail;
use App\User;

class UserObserver
{
    public function updated(User $user)
    {
        if ($user->email !== $user->getOriginal()['email']) {
            event(new UpdatedUserEmail($user));
        }

    }
}