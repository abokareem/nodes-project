<?php

namespace App\Events;

use App\Masternode;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class MasternodeReadyToCreate
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $node;

    /**
     * MasternodeReadyToCreate constructor.
     * @param Masternode $node
     */
    public function __construct(Masternode $node)
    {
        $this->node = $node;
    }
}
