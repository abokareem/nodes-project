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

    public function out(Masternode $mainNode)
    {
        $sameNode = Masternode::where([
            ['type', Masternode::PARTY_TYPE],
            ['state', Masternode::NEW_STATE]
        ])->first();

        if ($sameNode) {
            $this->handle($sameNode, $mainNode);
        }
    }

    protected function handle(Masternode $sameNode, Masternode $mainNode)
    {
        $invest = $this->user->investments()->where('node_id', $mainNode->id)->firstOrFail();

        switch ((int)$this->math->comparison($sameNode->bill->amount, $invest->amount)) {

            case MathInterface::EQUAL:

                $this->equal($sameNode, $mainNode);
                break;
            case MathInterface::LARGE:

                $this->larger($sameNode, $mainNode);
                break;
            case MathInterface::LESS:

                $this->less($sameNode, $mainNode);
                break;
            default:
                throw new \Exception('', 500);
        }
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