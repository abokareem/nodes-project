<?php

namespace App\Services;

use App\Jobs\LeaveFromNodeEqualJob;
use App\Jobs\LeaveFromNodeLargeJob;
use App\Jobs\LeaveFromNodeLessJob;
use App\Masternode;
use App\Services\Math\MathInterface;
use Illuminate\Support\Facades\Auth;

class LeaveNodeService
{
    private $user;
    private $math;

    public function __construct(MathInterface $math)
    {
        $this->math = $math;
        $this->user = Auth::user();
    }

    public function out(Masternode $node)
    {

    }

    protected function equal(Masternode $sameNode, Masternode $mainNode)
    {
        LeaveFromNodeEqualJob::dispatch($sameNode, $mainNode, $this->user);
    }

    protected function larger(Masternode $sameNode, Masternode $mainNode)
    {
        LeaveFromNodeLargeJob::dispatch($sameNode, $mainNode, $this->user);
    }

    protected function less(Masternode $sameNode, Masternode $mainNode)
    {
        LeaveFromNodeLessJob::dispatch($sameNode, $mainNode, $this->user);
    }
}