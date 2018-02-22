<?php

namespace App\Services;

use App\Events\AcceptedLeaveFromNode;
use App\Events\CreatedWithdrawal;
use App\Exceptions\WithdrawalAlreadyNotProcessing;
use App\Jobs\LeaveNodeJob;
use App\Masternode;
use App\Services\Math\MathInterface;
use App\Services\Settlement\SettlementHandler;
use App\Withdrawals;
use Illuminate\Support\Facades\Auth;

/**
 * Class LeaveNodeService
 * @package App\Services
 */
class LeaveNodeService
{
    /**
     * @var \Illuminate\Contracts\Auth\Authenticatable|null
     */
    private $user;
    /**
     * @var MathInterface
     */
    private $math;

    /**
     * LeaveNodeService constructor.
     * @param MathInterface $math
     */
    public function __construct(MathInterface $math)
    {
        $this->math = $math;
        $this->user = Auth::user();
    }

    /**
     * @param Masternode $node
     */
    public function out(Masternode $node)
    {
        $investor = $this->user->investments()
            ->where('node_id', $node->id)->firstOrFail();

        $withdrawal = $this->user->withdrawals()->create([
            'node_id' => $node->id,
            'state' => Withdrawals::PROCESSING_STATE,
            'amount' => $investor->amount
        ]);

        if ($node->state === Masternode::NEW_STATE) {
            $this->approve($withdrawal);
            event(new AcceptedLeaveFromNode($this->user));
            return;
        }

        if (SettlementHandler::hasSecondaryNode($node)) {
            LeaveNodeJob::dispatch($this->user, $node, $withdrawal);
            return;
        }
    }

    /**
     * @param Withdrawals $withdrawal
     * @throws WithdrawalAlreadyNotProcessing
     */
    public function approve(Withdrawals $withdrawal)
    {
        if ($withdrawal->state !== Withdrawals::PROCESSING_STATE) {
            throw new WithdrawalAlreadyNotProcessing();
        }

        $withdrawal->getConnection()->transaction(function () use ($withdrawal) {

            $math = app(MathInterface::class);

            $node = $withdrawal->node;
            $user = $withdrawal->user;

            $userBill = $user->bills()->where('currency_id', $node->currency_id)
                ->first();

            if (!$userBill) {
                $userBill = $user->bills()->create([
                    'currency_id' => $node->currency_id,
                    'amount' => '0'
                ]);
            }

            $nodeBill = $node->bill;
            $userBill->amount = $math->add($withdrawal->amount, $userBill->amount);
            $userBill->save();
            $nodeBill->amount = $math->sub($nodeBill->amount, $withdrawal->amount);
            $nodeBill->save();

            $user->investments()->where('node_id', $node->id)->delete();

            $user->transactions()->create([
                'currency_id' => $node->currency_id,
                'data' => [
                    'from' => $node,
                    'to' => $userBill
                ],
                'amount' => $withdrawal->amount
            ]);

            $withdrawal->update([
                'state' => Withdrawals::APPROVE_STATE
            ]);
            $node->update([
                'state' => Masternode::NEW_STATE
            ]);
        });
    }
}