<?php

namespace App\Services;

use App\Currency;
use App\Exceptions\InsolventException;
use App\Exceptions\UnsupportedMasternodeType;
use App\Masternode;
use App\Services\Math\MathInterface;
use App\User;
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

    public function create(Currency $currency, string $type)
    {
        switch ($type) {

            case Masternode::SINGLE_TYPE :

                $this->createSingle($currency);
                return;

            case Masternode::PARTY_TYPE :

                $this->createParty($currency);
                return;

            default:
                throw new UnsupportedMasternodeType();
        }

    }

    protected function createSingle(Currency $currency)
    {
        $userBill = $this->getUserBill($currency);

        $this->payable($currency->share->full_price, $userBill->amount);

        $currency->getConnection()->transaction($this->getSingleClosure($currency));
    }

    protected function getSingleClosure(Currency $currency)
    {
        return function () use ($currency) {

            $userBill = $this->getUserBill($currency->id);

            $userBill->amount = $this->math->sub($userBill->amount, $currency->share->full_price);
            $userBill->save();

            $node = $currency->nodes()->create([
                'state' => Masternode::PROCESSING_STATE,
                'type' => Masternode::SINGLE_TYPE,
                'price' => $currency->share->full_price
            ]);

            $node->bill()->create([
                'amount' => $currency->share->full_price
            ]);

            $this->user->transactions()->create([
                'currency_id' => $currency->id,
                'data' => $this->getDataForTransaction($node, $this->user),
                'amount' => $currency->share->full_price
            ]);
        };
    }

    protected function createParty(Currency $currency)
    {
        $userBill = $this->getUserBill($currency);

        $this->payable($currency->share->min_price, $userBill->amount);

        $currency->getConnection()->transaction($this->getPartyClosure($currency));
    }

    protected function getPartyClosure(Currency $currency)
    {
        return function () use ($currency) {

            $userBill = $this->getUserBill($currency->id);

            $userBill->amount = $this->math->sub($userBill->amount, $currency->share->min_price);
            $userBill->save();

            $node = $currency->nodes()->create([
                'state' => Masternode::NEW_STATE,
                'type' => Masternode::PARTY_TYPE,
                'price' => $currency->share->full_price
            ]);

            $node->bill()->create([
                'amount' => $currency->share->min_price
            ]);

            $this->user->transactions()->create([
                'currency_id' => $currency->id,
                'data' => $this->getDataForTransaction($node, $this->user),
                'amount' => $currency->share->min_price
            ]);
        };
    }

    /**
     * Get metadata for transaction record.
     *
     * @param Masternode $masternode
     * @param User $user
     * @return array
     */
    protected function getDataForTransaction(Masternode $masternode, User $user)
    {
        return [
            'masternode' => $masternode->toArray(),
            'user' => $user->toArray()
        ];
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
     * @param Currency $currency
     * @return mixed
     * @throws InsolventException
     */
    private function getUserBill(Currency $currency)
    {
        if (isset($this->userBill)) {
            return $this->userBill;
        }

        $this->userBill = $this->user->bills()->where('currency_id', $currency->id)->first();

        if (!$this->userBill) {
            throw new InsolventException();
        }

        return $this->userBill;
    }
}