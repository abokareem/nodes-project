<?php

namespace App\Services;

use App\Currency;
use App\Exceptions\InsolventException;
use App\Exceptions\NoFreeSharesException;
use App\Masternode;
use App\Services\Math\MathInterface;
use App\Transaction;
use App\Types\ShareBuyType;
use Illuminate\Support\Facades\Auth;

/**
 * Class ShareService
 * @package App\Services
 */
class ShareService
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
     * @var
     */
    private $buyType;

    /**
     * ShareService constructor.
     * @param MathInterface $math
     * @param ShareBuyType $buyType
     */
    public function __construct(MathInterface $math, ShareBuyType $buyType)
    {
        $this->user = Auth::user();
        $this->math = $math;
        $this->buyType = $buyType;
    }

    /**
     * Buy shares for masternode.
     *
     * @param Masternode $node
     * @param int $count
     * @throws InsolventException
     */
    public function buy(Masternode $node, int $count = 1)
    {
        $currency = $node->currency;
        $userBill = $this->getUserBill($currency);
        $price = $this->math->multiply($count, $currency->share->share_price);

        $this->freeShare($node, $price);
        $this->payable($price, $userBill->amount);

        $this->buyType->setPrice($price);
        $this->buyType->setNode($node);
        $this->buyType->setUserBill($userBill);

        $this->user->getConnection()->transaction(
            $this->getTransactionClosure($this->buyType)
        );
    }

    /**
     * @param ShareBuyType $buyType
     * @return \Closure
     */
    protected function getTransactionClosure(ShareBuyType $buyType)
    {
        return function () use ($buyType) {

            $userBill = $buyType->getUserBill();
            $price = $buyType->getPrice();
            $node = $buyType->getNode();

            $invest = $this->getUserInvest($node->currency, $node);

            $this->freeShare($node, $price);
            $this->payable($price, $userBill->amount);

            $userBill->amount = $this->math->sub($userBill->amount, $price);

            $userBill->save();

            $this->user->transactions()->create([
                'currency_id' => $node->currency_id,
                'type' => Transaction::BUY_SHARE_TYPE,
                'message' => Transaction::BUY_SHARE_MESSAGE,
                'data' => $this->getDataForTransaction($node),
                'amount' => $price
            ]);

            $node->bill->update([
                'amount' => $this->math->add($node->bill->amount, $price)
            ]);

            if ($invest) {
                $invest->update([
                    'amount' => $this->math->add($invest->amount, $price)
                ]);
            } else {
                $this->user->investments()->create([
                    'currency_id' => $node->currency_id,
                    'node_id' => $node->id,
                    'amount' => $price
                ]);
            }
        };
    }

    /**
     * Get metadata for transaction record.
     *
     * @param Masternode $masternode
     * @return array
     */
    protected function getDataForTransaction(Masternode $masternode)
    {
        return [
            'from' => $masternode->toArray()
        ];
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
        $userBill = $this->user->bills()->where('currency_id', $currency->id)->first();

        if (!$userBill) {
            throw new InsolventException();
        }

        return $userBill;
    }

    /**
     * @param Currency $currency
     * @param Masternode $node
     * @return mixed
     */
    private function getUserInvest(Currency $currency, Masternode $node)
    {
        return $this->user->investments()->where([
            ['currency_id', $currency->id],
            ['node_id', $node->id]
        ])->first();
    }

    /**
     * @param Masternode $node
     * @param int $price
     * @throws NoFreeSharesException
     */
    private function freeShare(Masternode $node, int $price)
    {
        $nodeFullPrice = $node->price;
        $nodeBillAmount = $node->bill->amount;
        $freeShare = $this->math->sub($nodeFullPrice, $nodeBillAmount);
        if ($freeShare < $price) {
            throw new NoFreeSharesException();
        }
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
}