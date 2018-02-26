<?php

namespace App\Jobs;

use App\Masternode;
use App\Services\Settlement\SettlementHandler;
use App\User;
use App\Withdrawals;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class LeaveNodeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;
    private $node;
    private $withdrawal;

    /**
     * LeaveNodeJob constructor.
     * @param User $user
     * @param Masternode $node
     * @param Withdrawals $withdrawal
     */
    public function __construct(User $user, Masternode $node, Withdrawals $withdrawal)
    {
        $this->user = $user;
        $this->node = $node;
        $this->withdrawal = $withdrawal;
    }

    /**
     * Execute the job.
     *
     * @param SettlementHandler $handler
     * @return void
     */
    public function handle(SettlementHandler $handler)
    {
        $handler->handle($this->node, $this->user, $this->withdrawal)->solve();
    }
}
