<?php

namespace App\Services;

use App\Currency;
use App\Exceptions\InsolventException;
use App\Services\Math\MathInterface;
use App\User;
use App\UserBill;
use Illuminate\Support\Facades\Auth;
use Ixudra\Curl\Facades\Curl;

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

    /**
     * @param Currency $currency
     * @return UserBill
     */
    public function put(Currency $currency): UserBill
    {
        $user = Auth::user();
        $userBill = $user->bills()->where('currency_id', $currency->id)->first();

        if ($userBill) {
            return $userBill;
        }

        $wallet = Curl::to(config('wallets.urls.new'))
            ->withData(['currency' => $currency, 'user' => Auth::user()])
            ->asJson()
            ->post();

        $newBill = $user->bills()->create([
            'currency_id' => $currency->id,
            'uuid' => $wallet['hash']
        ]);

        return $newBill;
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
            'data' => $this->getDataForTransaction($currency, $user),
            'amount' => $amount
        ]);

        return $userBill;
    }

    /**
     * @param Currency $currency
     * @param $price
     * @return mixed
     * @throws InsolventException
     */
    public function withdrawalMoney(Currency $currency, $price)
    {
        $user = Auth::user();

        $userBill = $user->bills()->where('currency_id', $currency->id)->firstOrFail();

        if ((int)$this->math->comparison($price, $userBill->amount) === 1) {
            throw new InsolventException();
        }

        $userBill->amount = $this->math->sub($userBill->amount, $price);

        $user->transactions()->create([
            'currency_id' => $currency->id,
            'data' => $this->getDataForTransaction($currency, $user),
            'amount' => $price
        ]);

        return $userBill;
    }

    /**
     * @param Currency $currency
     * @param User $user
     * @return array
     */
    private function getDataForTransaction(Currency $currency, User $user)
    {
        return [
            'currency' => $currency,
            'user' => $user
        ];
    }
}