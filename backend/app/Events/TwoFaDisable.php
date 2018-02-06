<?php

namespace App\Events;

use App\UserAction;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class TwoFaDisable
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * TwoFaDisable constructor.
     */
    public function __construct()
    {
        $this->message = UserAction::TWO_FA_DISABLED;
    }
}
