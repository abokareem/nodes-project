<?php

namespace App\Services\Settlement;

use App\Investment;
use App\Services\Math\MathInterface;
use App\Transaction;
use App\Types\SettlementType;
use Carbon\Carbon;

/**
 * Class MainNodeService
 * @package App\Services\Settlement
 */
class MainNodeService
{
    private $type;

    /**
     * MainNodeService constructor.
     * @param SettlementType $type
     */
    public function __construct(SettlementType $type)
    {
        $this->type = $type;
    }

    /**
     * @param array $investors
     */
    public function migrate(array $investors)
    {
        $newInvestors = $this->updateInvestors($investors);
        $this->createNewInvestors($newInvestors);
        $this->updateBills($investors);
    }

    /**
     * @param array $transferInvestors
     * @return array
     */
    protected function updateInvestors(array $transferInvestors)
    {
        $mainInvestors = $this->type->getMainNode()->investments;
        $math = app(MathInterface::class);
        $newInvestors = [];

        foreach ($transferInvestors as $transferInvestor) {
            $newInvestors[] = $transferInvestor;

            foreach ($mainInvestors as $mainInvestor) {

                if ($transferInvestor->user_id === $mainInvestor->user_id) {

                    $mainInvestor->amount = $math
                        ->add($mainInvestor->amount, $transferInvestor->amount);

                    $mainInvestor->save();

                    Transaction::create([
                        'user_id' => $mainInvestor->user_id,
                        'currency_id' => $mainInvestor->currency_id,
                        'data' => ['from' => $transferInvestor, 'to' => $mainInvestor],
                        'amount' => $transferInvestor->amount
                    ]);

                    array_pop($newInvestors);

                    continue;
                }
            }
        }

        return $newInvestors;
    }

    /**
     * @param array $investors
     */
    protected function createNewInvestors(array $investors)
    {
        $transactions = [];
        $newInvestors = [];

        foreach ($investors as $investor) {
            unset($investor->id);

            $investor->node_id = $this->type->getMainNode()->id;
            $investor->created_at = Carbon::now();
            $investor->updated_at = Carbon::now();
            $newInvestors[] = $investor->toArray();

            $transactions[] = [
                'user_id' => $investor->user_id,
                'currency_id' => $investor->currency_id,
                'data' => json_encode([
                    'from' => $this->type->getSecondaryNode(),
                    'to' => $this->type->getMainNode()
                ]),
                'amount' => $investor->amount,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }

        Transaction::insert($transactions);
        Investment::insert($newInvestors);
    }

    /**
     * @param array $investors
     */
    protected function updateBills(array $investors)
    {
        $math = app(MathInterface::class);
        $amount = 0;

        foreach ($investors as $investor) {
            $amount = $math->add($amount, $investor->amount);
        }

        $mainNodeAmount = $math->sub(
            $this->type->getMainNode()->bill->amount, $this->type->getInvestment()->amount
        );

        $this->type->getMainNode()->bill()->update([
            'amount' => $math->add($amount, $mainNodeAmount)
        ]);

        $this->type->getSecondaryNode()->bill()->update([
            'amount' => $math->sub($this->type->getSecondaryNode()->bill->amount, $amount)
        ]);

        Transaction::create([
            'user_id' => $this->type->getUser()->id,
            'currency_id' => $this->type->getMainNode()->currency_id,
            'data' => [
                'from' => $this->type->getSecondaryNode(),
                'to' => $this->type->getMainNode()
            ],
            'amount' => $amount
        ]);
    }
}