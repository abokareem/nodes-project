<?php

namespace App\Services;

use App\Currency;
use App\Exceptions\InsolventException;
use App\Exceptions\System\FreeWalletNotExist;
use App\FreeWallet;
use App\Services\Math\MathInterface;
use App\Transaction;
use App\User;
use App\UserBill;
use App\WithdrawalMoney;
use Illuminate\Support\Facades\Auth;

/**
 * Class UserBillService
 * @package App\Services
 */
class UserBillService
{

    /**
     * @var MathInterface
     */
    private $math;

    /**
     * UserBillService constructor.
     * @param MathInterface $math
     */
    public function __construct(MathInterface $math)
    {
        $this->math = $math;
    }

    public function create(Currency $currency)
    {
        $bill = $this->put($currency);
        if ($bill) {
            return $bill;
        }

        $user = Auth::user();

        $user->getConnection()->transaction(function () use ($currency, $user) {

            $freeWallet = FreeWallet::where([
                ['currency_id', $currency->id],
                ['state', FreeWallet::FREE_STATE]
            ])->lockForUpdate()->first();

            if (!$freeWallet) {
                throw new FreeWalletNotExist();
            }

            $userBill = $user->bills()->where('currency_id', $currency->id)->first();

            if ($userBill) {

                $userBill->update([
                    'uuid' => $freeWallet->hash
                ]);
            } else {

                $user->bills()->create([
                    'currency_id' => $currency->id,
                    'uuid' => $freeWallet->hash
                ]);
            }

            $freeWallet->state = FreeWallet::BUSY_STATE;
            $freeWallet->save();

        });

        $bill = $user->bills()->where('currency_id', $currency->id)->firstOrFail();

        return $bill;
    }

    /**
     * @param Currency $currency
     * @return UserBill
     */
    public function put(Currency $currency)
    {
        $user = Auth::user();
        $userBill = $user->bills()->where('currency_id', $currency->id)->first();

        if ($userBill && !is_null($userBill->uuid)) {
            return $userBill;
        }

        return null;
    }

    /**
     * @param Currency $currency
     * @param User $user
     * @param $amount
     * @return \Illuminate\Database\Eloquent\Model|static
     */
    public function acceptPut(Currency $currency, User $user, $amount)
    {
        $userBill = $user->bills()->where('currency_id', $currency->id)->firstOrFail();

        $userBill->amount = $this->math->add($userBill->amount, $amount);

        $user->transactions()->create([
            'currency_id' => $currency->id,
            'data' => $this->getDataForTransaction($userBill),
            'amount' => $amount
        ]);

        return $userBill;
    }

    /**
     * @param UserBill $bill
     * @param string $wallet
     * @param $price
     * @return UserBill
     */
    public function withdrawalMoney(UserBill $bill, string $wallet, $price)
    {
        $user = Auth::user();
        $user->getConnection()->transaction(function () use ($user, $price, $bill, $wallet) {

            if ((int)$this->math->comparison($price, $bill->amount) === 1) {
                throw new InsolventException();
            }

            $bill->amount = $this->math->sub($bill->amount, $price);
            $bill->save();

            $bill->withdrawal()->create([
                'external_user_wallet' => $wallet,
                'state' => WithdrawalMoney::PROCESSING,
                'amount' => $price
            ]);

            $user->transactions()->create([
                'currency_id' => $bill->currency->id,
                'type' => Transaction::WITHDRAW_BILL_TYPE,
                'message' => Transaction::WITHDRAW_BILL_MESSAGE,
                'data' => $this->getDataForTransaction($bill),
                'amount' => $price
            ]);
        });

        return $bill;
    }

    /**
     * @param UserBill $bill
     * @return array
     */
    private function getDataForTransaction(UserBill $bill)
    {
        return [
            'from' => $bill
        ];
    }
}