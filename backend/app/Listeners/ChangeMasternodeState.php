<?php

namespace App\Listeners;

use App\Events\MasternodeReadyToCreate;
use App\Masternode;

class ChangeMasternodeState
{
    /**
     * Handle the event.
     *
     * @param  MasternodeReadyToCreate $event
     * @return void
     */
    public function handle(MasternodeReadyToCreate $event)
    {
        $node = $event->node;
        $node->state = Masternode::PROCESSING_STATE;
        $node->save();
    }
}
