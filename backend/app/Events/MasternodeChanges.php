<?php

namespace App\Events;

use App\Masternode;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class MasternodeChanges
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $node;

    /**
     * Create a new event instance.
     *
     * @param Masternode $node
     */
    public function __construct(Masternode $node)
    {
        $this->node = $node;
    }
}
