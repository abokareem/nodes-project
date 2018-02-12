<?php

namespace App\Services;

use App\ActiveMasternode;
use App\Exceptions\InsolventException;
use App\Exceptions\UnsupportedMasternodeType;
use App\Masternode;
use App\Services\Math\MathInterface;
use Illuminate\Support\Facades\Auth;

class ActiveNodeService
{
    private $math;
    private $user;
    private $userBill;

    public function __construct(MathInterface $math)
    {
        $this->user = Auth::user();
        $this->math = $math;
    }

    public function activate(Masternode $node, string $type)
    {
        switch ($type) {

            case ActiveMasternode::SINGLE_TYPE :

                $this->activateSingle($node);
                return;

            case ActiveMasternode::PARTY_TYPE :

                $this->activateParty($node);
                return;

            default:
                throw new UnsupportedMasternodeType();
        }

    }

    protected function activateSingle(Masternode $node)
    {
        $userBill = $this->getUserBill($node->currency_id);

        $this->payable($node->price, $userBill->amount);

        $node->getConnection()->transaction($this->getSingleClosure($node));
    }

    protected function getSingleClosure(Masternode $node)
    {
        return function () use ($node) {

            $userBill = $this->getUserBill($node->currency_id);

            $userBill->amount = $this->math->sub($userBill->amount, $node->price);
            $userBill->save();

            $activeNode = ActiveMasternode::create([
                'masternode_id' => $node->id,
                'state' => ActiveMasternode::PROCESSING_STATE,
                'type' => ActiveMasternode::SINGLE_TYPE
            ]);

            $activeNode->share()->create([
                'price' => $node->share->price,
                'count' => $node->share->count
            ]);

            $activeNode->bill()->create([
                'currency_id' => $node->currency_id,
                'amount' => $node->price
            ]);
        };
    }

    protected function activateParty(Masternode $node)
    {

    }

    protected function getPartyClosure(Masternode $node)
    {
        return function () use ($node) {

            $activeNode = ActiveMasternode::create([
                'masternode_id' => $node->id,
                'state' => 'processing',
                'type' => ActiveMasternode::PARTY_TYPE
            ]);

            $activeNode->share()->create([
                'price' => $node->share->price,
                'count' => $node->share->count
            ]);

            $activeNode->bill()->create([
                'currency_id' => $node->currency_id
            ]);
        };
    }

    /**
     * Check user payable.
     *
     * @param string $price
     * @param string $amount
     * @throws InsolventException
     */
    private function payable(string $price, string $amount)
    {
        if ((int)$this->math->comparison($price, $amount) === 1) {
            throw new InsolventException();
        }
    }

    /**
     * Get user bill.
     *
     * @param int $id
     * @return mixed
     * @throws InsolventException
     */
    private function getUserBill(int $id)
    {
        if (isset($this->userBill)) {
            return $this->userBill;
        }

        $this->userBill = $this->user->bills()->where('currency_id', $id)->first();

        if (!$this->userBill) {
            throw new InsolventException();
        }

        return $this->userBill;
    }
}