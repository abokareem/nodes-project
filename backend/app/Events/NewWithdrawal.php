<?php

namespace App\Events;

use App\Withdrawals;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class NewWithdrawal
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $withdrawal;

    /**
     * NewWithdrawal constructor.
     * @param Withdrawals $withdrawal
     */
    public function __construct(Withdrawals $withdrawal)
    {
        $this->withdrawal = $withdrawal;
    }
}
