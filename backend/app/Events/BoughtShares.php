<?php

namespace App\Events;

use App\UserAction;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class BoughtShares
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * BoughtShares constructor.
     */
    public function __construct()
    {
        $this->message = UserAction::BUY_SHARES;
    }
}
