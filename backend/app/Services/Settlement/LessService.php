<?php

namespace App\Services\Settlement;

use App\Masternode;
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

            $userService = app(UserSettlementService::class, [
                'type' => $this->type
            ]);

            $secondaryNodeService = app(SecondaryNodeService::class, [
                'node' => $this->type->getSecondaryNode()
            ]);

            $mainNodeService = app(MainNodeService::class, [
                'type' => $this->type
            ]);

            $investors = $secondaryNodeService->getInvestorsByAmount(
                $this->type->getInvestment()->amount
            );

            $userService->updateBill($secondaryNodeService->getTransferAmount($investors));

            $this->type->getInvestment()->delete();

            $mainNodeService->migrate($investors);

            $secondaryNodeService->deleteGivenInvestors();

            $this->type->getSecondaryNode()->bill()->delete();
            $this->type->getSecondaryNode()->investments()->delete();
            $this->type->getSecondaryNode()->delete();

            $this->type->getMainNode()->update([
                'state' => Masternode::UNSTABLE_STATE
            ]);

        });
    }
}