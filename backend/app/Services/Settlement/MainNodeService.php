<?php

namespace App\Services\Settlement;

use App\Investment;
use App\Masternode;
use App\Services\Math\MathInterface;
use App\Transaction;
use Carbon\Carbon;

/**
 * Class MainNodeService
 * @package App\Services\Settlement
 */
class MainNodeService
{
    /**
     * @var Masternode
     */
    private $node;
    /**
     * @var Masternode
     */
    private $secondaryNode;

    /**
     * MainNodeService constructor.
     * @param Masternode $node
     * @param Masternode $secondaryNode
     */
    public function __construct(Masternode $node, Masternode $secondaryNode)
    {
        $this->node = $node;
        $this->secondaryNode = $secondaryNode;
    }

    /**
     * @param array $investors
     */
    public function migrate(array $investors)
    {
        $newInvestors = $this->updateInvestors($investors);
        $this->createNewInvestors($newInvestors);
    }

    /**
     * @param array $transferInvestors
     * @return array
     */
    protected function updateInvestors(array $transferInvestors)
    {
        $mainInvestors = $this->node->investments;
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

            $investor->node_id = $this->node->id;
            $investor->created_at = Carbon::now();
            $investor->updated_at = Carbon::now();
            $newInvestors[] = $investor->toArray();

            $transactions[] = [
                'user_id' => $investor->user_id,
                'currency_id' => $investor->currency_id,
                'data' => json_encode([
                    'from' => $this->secondaryNode,
                    'to' => $this->node
                ]),
                'amount' => $investor->amount,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }

        Transaction::insert($transactions);
        Investment::insert($newInvestors);
    }
}