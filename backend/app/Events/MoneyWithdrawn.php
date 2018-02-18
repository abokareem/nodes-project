<?php

namespace App\Events;

use App\UserAction;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class MoneyWithdrawn
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * MoneyWithdrawn constructor.
     */
    public function __construct()
    {
        $this->message = UserAction::WITHDRAWAL_MONEY;
    }
}
