<?php

namespace App\Events;

use App\EmailConfirmation;
use App\UserAction;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class ResendUserRegisteredEmail
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $emailConfirmation;
    public $user;
    public $message;

    /**
     * ResendUserRegisteredEmail constructor.
     * @param EmailConfirmation $emailConfirmation
     */
    public function __construct(EmailConfirmation $emailConfirmation)
    {
        $this->emailConfirmation = $emailConfirmation;

        $this->user = $emailConfirmation->user;

        $this->message = UserAction::RESEND_CONFIRMED_EMAIL;
    }
}
