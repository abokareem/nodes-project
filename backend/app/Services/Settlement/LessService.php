<?php

namespace App\Services\Settlement;

use App\Masternode;
use App\Services\Math\MathInterface;
use App\Types\SettlementType;

class LessService implements SettlementInterface
{
    /**
     * @var SettlementType
     */
    private $type;

    /**
     * EqualService constructor.
     * @param SettlementType $type
     */
    public function __construct(SettlementType $type)
    {
        $this->type = $type;
    }

    public function solve()
    {
        $this->type->getMainNode()->getConnection()->transaction(function () {

            $math = app(MathInterface::class);
            $leavedInvestor = $this->type->getInvestment();
            $secondaryNode = $this->type->getSecondaryNode();

            $userService = app(UserSettlementService::class, [
                'type' => $this->type
            ]);

            $secondaryNodeService = app(SecondaryNodeService::class, [
                'node' => $this->type->getSecondaryNode()
            ]);

            $mainNodeService = app(MainNodeService::class, [
                'type' => $this->type
            ]);

            $investors = $secondaryNodeService->getInvestorsByAmount($leavedInvestor->amount);

            $transferAmount = $secondaryNodeService->getTransferAmount($investors);
            $userService->updateBill($transferAmount);

            $mainNodeService->migrate($investors);

            $secondaryNodeService->deleteGivenInvestors();

            $secondaryNode->bill()->delete();
            $secondaryNode->investments()->delete();
            $secondaryNode->delete();


            $this->type->getMainNode()->update([
                'state' => Masternode::UNSTABLE_STATE
            ]);

            $this->type->getInvestment()->update([
                'amount' => $math->sub($leavedInvestor->amount, $transferAmount)
            ]);

            $withdrawal = $this->type->getWithdrawal();
            $withdrawal->update([
                'amount' => $math->sub($withdrawal->amount, $transferAmount)
            ]);

        });
    }
}