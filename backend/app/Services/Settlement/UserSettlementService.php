<?php

namespace App\Services\Settlement;

use App\Services\Math\MathInterface;
use App\Transaction;
use App\Types\SettlementType;

class UserSettlementService
{
    private $type;

    public function __construct(SettlementType $type)
    {
        $this->type = $type;
    }

    public function updateBill($amount)
    {
        $math = app(MathInterface::class);

        $userBill = $this->type->getUser()->bills()->where('currency_id',
            $this->type->getSecondaryNode()->currency_id)->first();

        if (!$userBill) {
            $userBill = $this->type->getUser()->bills()->create([
                'currency_id' => $this->type->getSecondaryNode()->currency_id,
                'amount' => '0'
            ]);
        }

        $userBill->amount = $math->add($userBill->amount, $amount);

        $userBill->save();

        Transaction::create([
            'user_id' => $this->type->getUser()->id,
            'currency_id' => $userBill->currency_id,
            'type' => Transaction::SETTLEMENT_TYPE,
            'message' => Transaction::SETTLEMENT_MESSAGE,
            'data' => ['from' => $this->type->getSecondaryNode()],
            'amount' => $amount
        ]);
    }
}