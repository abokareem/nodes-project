<?php

namespace App\Events;

use App\EmailConfirmation;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class ResendUserRegisteredEmail
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $emailConfirmation;

    /**
     * ResendUserRegisteredEmail constructor.
     * @param EmailConfirmation $emailConfirmation
     */
    public function __construct(EmailConfirmation $emailConfirmation)
    {
        $this->emailConfirmation = $emailConfirmation;
    }
}
