<?php

namespace App\Listeners;

use App\Events\MasternodeChanges;
use App\Masternode;
use App\Withdrawals;

class ChangeMasternodeStableState
{
    /**
     * Handle the event.
     *
     * @param  MasternodeChanges $event
     * @return void
     */
    public function handle(MasternodeChanges $event)
    {
        $node = $event->node;
        $count = $node->withdrawals()->where('state', Withdrawals::PROCESSING_STATE)
            ->count();
        if ($count) {
            $node->state = Masternode::STABLE_STATE;
            $node->save();
        } else {
            $node->state = Masternode::UNSTABLE_STATE;
            $node->save();
        }
    }
}
