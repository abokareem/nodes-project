<?php

namespace App\Services;

use App\Commission;
use App\Currency;
use App\Exceptions\InsolventException;
use App\Exceptions\MaxMasternodes;
use App\Exceptions\UnsupportedMasternodeType;
use App\Masternode;
use App\Services\Math\MathInterface;
use App\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class NodeService
 * @package App\Services
 */
class NodeService
{
    /**
     * @var MathInterface
     */
    private $math;
    /**
     * @var \Illuminate\Contracts\Auth\Authenticatable|null
     */
    private $user;
    /**
     * @var
     */
    private $userBill;

    /**
     * NodeService constructor.
     * @param MathInterface $math
     * @param Request $request
     */
    public function __construct(MathInterface $math, Request $request)
    {
        $this->user = Auth::user();
        $this->math = $math;
        $this->request = $request;
    }

    /**
     * @param $amount
     * @param Masternode $node
     */
    public function profit($amount, Masternode $node)
    {
        $percent = Commission::where('type', $node->type)->first();

        if ($percent) {
            $percent = $percent->percent;
        }

        $amount = $this->math->sub($amount, $this->math->percent($amount, $percent));

        $node->getConnection()->transaction(function () use ($node, $amount) {
            $investors = $node->investments;

            foreach ($investors as $investor) {

                $bill = $investor->user->bills()->where('currency_id', $node->currency_id)
                    ->firstOrFail();
                $percentInvestments = $this->math->divideScale(
                    $this->math->multiply($investor->amount, 100), $node->price, 2
                );
                $profit = $this->math->percent($amount, $percentInvestments);
                $bill->amount = $this->math->add($bill->amount, $profit);

                $userProfits = $investor->user->profits()->where('node_id', $node->id)->first();

                if ($userProfits) {

                    $userProfits->per_day = $profit;
                    $userProfits->full = $this->math->add($userProfits->full, $profit);
                    $userProfits->save();
                    continue;
                }

                $investor->user->profits()->create([
                    'node_id' => $node->id,
                    'per_day' => $profit,
                    'full' => $profit
                ]);
            }
        });
    }

    /**
     * @param Currency $currency
     */
    public function create(Currency $currency)
    {
        $type = $this->request->get('type');

        $this->typeExists($type);
        $this->{$type}($currency);
    }

    /**
     * @param Currency $currency
     */
    protected function single(Currency $currency)
    {
        $userBill = $this->getUserBill($currency);

        $this->payable($currency->share->full_price, $userBill->amount);

        $currency->getConnection()->transaction($this->getSingleClosure($currency));
    }

    /**
     * @param Currency $currency
     */
    protected function party(Currency $currency)
    {
        $userBill = $this->getUserBill($currency);

        $price = $this->math->multiply($this->request->get('count') ?? 1,
            $currency->share->share_price);

        $this->payable($price, $userBill->amount);

        $currency->getConnection()->transaction($this->getPartyClosure($currency));
    }

    /**
     * @param Currency $currency
     * @return \Closure
     */
    protected function getSingleClosure(Currency $currency)
    {
        return function () use ($currency) {

            $userBill = $this->getUserBill($currency);
            $price = $currency->share->full_price;

            $userBill->amount = $this->math->sub($userBill->amount, $price);
            $userBill->save();

            $node = $currency->nodes()->create([
                'state' => Masternode::PROCESSING_STATE,
                'type' => Masternode::SINGLE_TYPE,
                'price' => $price
            ]);

            $node->bill()->create([
                'amount' => $price
            ]);

            $this->user->transactions()->create([
                'currency_id' => $currency->id,
                'type' => Transaction::BUY_SHARE_TYPE,
                'message' => Transaction::BUY_SHARE_MESSAGE,
                'data' => $this->getDataForTransaction($node),
                'amount' => $price
            ]);

            $this->user->investments()->create([
                'currency_id' => $currency->id,
                'node_id' => $node->id,
                'amount' => $price
            ]);
        };
    }

    /**
     * @param Currency $currency
     * @return \Closure
     */
    protected function getPartyClosure(Currency $currency)
    {
        return function () use ($currency) {

            $userBill = $this->getUserBill($currency);

            $price = $this->math->multiply($this->request->get('count') ?? 1,
                $currency->share->share_price);

            $userBill->amount = $this->math->sub($userBill->amount, $price);
            $userBill->save();


            $masternode = Masternode::where([
                ['currency_id', $currency->id],
                ['state', Masternode::NEW_STATE],
                ['type', Masternode::PARTY_TYPE]
            ])->count();

            if ($masternode >= config('masternode.max')) {
                throw new MaxMasternodes();
            }

            $node = $currency->nodes()->create([
                'state' => Masternode::NEW_STATE,
                'type' => Masternode::PARTY_TYPE,
                'price' => $currency->share->full_price
            ]);

            $node->bill()->create([
                'amount' => $price
            ]);

            $this->user->transactions()->create([
                'currency_id' => $currency->id,
                'type' => Transaction::BUY_SHARE_TYPE,
                'message' => Transaction::BUY_SHARE_MESSAGE,
                'data' => $this->getDataForTransaction($node),
                'amount' => $price
            ]);

            $this->user->investments()->create([
                'currency_id' => $currency->id,
                'node_id' => $node->id,
                'amount' => $price
            ]);
        };
    }

    /**
     * Get metadata for transaction record.
     *
     * @param Model $masternode
     * @return array
     */
    protected function getDataForTransaction(Model $masternode)
    {
        return [
            'from' => $masternode->toArray()
        ];
    }

    /**
     *
     * Check type method on exists.
     *
     * @param string $type
     * @throws UnsupportedMasternodeType
     */
    private function typeExists(string $type)
    {
        if (!method_exists($this, $type)) {
            throw new UnsupportedMasternodeType();
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