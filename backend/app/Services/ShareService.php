<?php

namespace App\Services;

use App\ActiveMasternode;
use App\ActiveMasternodeShares;
use App\Currency;
use App\Exceptions\InsolventException;
use App\Services\Math\MathInterface;
use App\Types\ShareBuyType;
use App\User;
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
     * @param ActiveMasternodeShares $share
     * @param int $count
     * @throws InsolventException
     */
    public function buy(ActiveMasternodeShares $share, int $count)
    {
        $currency = $share->masternode->bill->currency;
        $userBill = $this->getUserBill($currency);
        $price = $this->math->multiply($share->price, $count);

        $this->payable($price, $userBill->amount);

        $this->buyType->setPrice($price);
        $this->buyType->setShare($share);
        $this->buyType->setSharesCount($count);
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
            $share = $buyType->getShare();
            $masternode = $share->masternode;
            $count = $buyType->getSharesCount();
            $existsUserShare = $this->user->shares()->where('share_id', $share->id)->first();

            $userBill->amount = $this->math->sub($userBill->amount, $price);

            $userBill->save();

            $this->user->transactions()->create([
                'currency_id' => $masternode->bill->currency->id,
                'data' => $this->getDataForTransaction($masternode, $this->user),
                'amount' => $price
            ]);

            $masternode->bill()->update([
                'amount' => $this->math->add($masternode->bill->amount, $price)
            ]);

            if ($existsUserShare) {
                $existsUserShare->update([
                    'count' => $this->math->add($existsUserShare->count, $count)
                ]);
            } else {
                $this->user->shares()->create([
                    'share_id' => $share->id,
                    'count' => $buyType->getSharesCount()
                ]);
            }

            $share->update([
                'count' => $this->math->sub($share->count, $count)
            ]);
        };
    }

    /**
     * Get metadata for transaction record.
     *
     * @param ActiveMasternode $masternode
     * @param User $user
     * @return array
     */
    protected function getDataForTransaction(ActiveMasternode $masternode, User $user)
    {
        return [
            'masternode' => $masternode->toArray(),
            'user' => $user->toArray()
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