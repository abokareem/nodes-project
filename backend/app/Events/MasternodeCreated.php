<?php

namespace App\Events;

use App\UserAction;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class MasternodeCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * MasternodeCreated constructor.
     */
    public function __construct()
    {
        $this->message = UserAction::MASTERNODE_CREATED;
    }
}
