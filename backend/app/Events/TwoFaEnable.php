<?php

namespace App\Events;

use App\UserAction;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class TwoFaEnable
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * TwoFaEnable constructor.
     */
    public function __construct()
    {
        $this->message = UserAction::TWO_FA_ENABLED;
    }
}
