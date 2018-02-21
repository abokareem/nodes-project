<?php

namespace App\Jobs;

use App\Investment;
use App\Masternode;
use App\Services\Math\MathInterface;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class LeaveFromNodeLargeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;
    private $sameNode;
    private $mainNode;
    /**
     * @var MathInterface
     */
    private $math;

    /**
     * Create a new job instance.
     *
     * @param Masternode $sameNode
     * @param Masternode $mainNode
     * @param User $user
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
    }
}
