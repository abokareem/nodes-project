<?php

namespace App\Events;

use App\UserAction;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class AcceptedPutMoney
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct()
    {
        $this->message = UserAction::PUT_MONEY;
    }
}
