<?php

namespace App\Services\Settlement;

use App\Investment;
use App\Services\Math\MathInterface;
use App\Transaction;
use App\Types\SettlementType;

/**
 * Class EqualService
 * @package App\Services\Settlement
 */
class EqualService implements SettlementInterface
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

    /**
     *
     */
    public function solve()
    {
        $this->type->getMainNode()->getConnection()->transaction(function () {

            $updatedUsers = $this->updateInvestors();

            $newInvestors = $this->type->getSecondaryNode()->investments()
                ->whereNotIn('user_id', $updatedUsers)->get();


            $this->createNewInvestors($newInvestors);
            $this->updateUserBill();

            $this->type->getSecondaryNode()->bill()->delete();
            //TODO
            $this->type->getSecondaryNode()->delete();
            $this->type->getInvestment()->delete();
        });
    }

    /**
     * @return array
     */
    protected function updateInvestors()
    {
        $secondaryInvestors = $this->type->getSecondaryNode()->investments;
        $mainInvestors = $this->type->getMainNode()->investments;
        $math = app(MathInterface::class);

        $updatedUsers = [];

        foreach ($secondaryInvestors as $secondaryInvestor) {

            foreach ($mainInvestors as $mainInvestor) {

                if ($secondaryInvestor->user_id === $mainInvestor->user_id) {

                    $mainInvestor->amount = $math
                        ->add($mainInvestor->amount, $secondaryInvestor->amount);
                    $mainInvestor->save();

                    Transaction::create([
                        'user_id' => $mainInvestor->user_id,
                        'currency_id' => $mainInvestor->currency_id,
                        'data' => ['from' => $secondaryInvestor, 'to' => $mainInvestor],
                        'amount' => $secondaryInvestor->amount
                    ]);

                    $secondaryInvestor->delete();

                    $updatedUsers[] = $secondaryInvestor->user_id;
                    continue;
                }
            }
        }

        return $updatedUsers;
    }

    /**
     * @param $newInvestors
     */
    protected function createNewInvestors($newInvestors)
    {
        foreach ($newInvestors as $newInvestor) {
            unset($newInvestor->id);
            $newInvestor->node_id = $this->type->getMainNode()->id;
        }

        Investment::insert($newInvestors->toArray());
    }

    /**
     *
     */
    protected function updateUserBill()
    {
        $math = app(MathInterface::class);

        $userBill = $this->type->getUser()->bills()->where('currency_id',
            1)->first();
        //TODO
        //dd($this->type->getMainNode()->bill->);

        $userBill->amount = $math->add($userBill->amount, $this->type->getInvestment()->amount);

        $userBill->save();

        Transaction::create([
            'user_id' => $this->type->getUser()->id,
            'currency_id' => $userBill->currency_id,
            'data' => ['from' => $this->type->getSecondaryNode(), 'to' => $userBill],
            'amount' => $this->type->getInvestment()->amount
        ]);
    }
}