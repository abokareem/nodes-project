<?php

namespace App\Services\Settlement;

use App\Masternode;
use App\Services\Math\MathInterface;
use App\Types\SettlementType;
use App\User;
use App\Withdrawals;

/**
 * Class SettlementHandler
 * @package App\Services\Settlement
 */
class SettlementHandler
{
    public function handle(Masternode $node, User $user, Withdrawals $withdrawal)
    {
        $secondaryNode = Masternode::where([
            ['currency_id', $node->currency_id],
            ['type', Masternode::PARTY_TYPE],
            ['state', Masternode::NEW_STATE]
        ])->first();

        if ($secondaryNode) {

            $invest = $user->investments()->where('node_id', $node->id)->firstOrFail();

            $settlementType = app(SettlementType::class);
            $settlementType->setUser($user);
            $settlementType->setMainNode($node);
            $settlementType->setSecondaryNode($secondaryNode);
            $settlementType->setInvestment($invest);
            $settlementType->setWithdrawal($withdrawal);

            return $this->makeService($settlementType);
        }

        return false;
    }

    public static function hasSecondaryNode(Masternode $node)
    {
        $secondaryNode = Masternode::where([
            ['currency_id', $node->currency_id],
            ['type', Masternode::PARTY_TYPE],
            ['state', Masternode::NEW_STATE]
        ])->first();

        if ($secondaryNode) {

            return true;
        }

        return false;
    }

    protected function makeService(SettlementType $type): SettlementInterface
    {
        switch ($this->getComparison($type)) {

            case MathInterface::EQUAL:

                return new EqualService($type);

            case MathInterface::LARGE:

                return new LargeService($type);

            case MathInterface::LESS:

                return new LessService($type);

            default:
                throw new \Exception('', 500);
        }
    }

    private function getComparison(SettlementType $type)
    {
        $math = app(MathInterface::class);

        return (int)$math->comparison(
            $type->getSecondaryNode()->bill->amount, $type->getInvestment()->amount
        );
    }
}