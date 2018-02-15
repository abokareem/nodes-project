<?php

namespace App\Services;

use App\Currency;
use App\Exceptions\InsolventException;
use App\Exceptions\UnsupportedMasternodeType;
use App\Masternode;
use App\Services\Math\MathInterface;
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

        $this->payable($currency->share->min_price, $userBill->amount);

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
                'data' => $this->getDataForTransaction($node, $this->user),
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

            $price = $this->request->get('price') ?? $currency->share->min_price;

            $userBill->amount = $this->math->sub($userBill->amount, $price);
            $userBill->save();

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
            'masternode' => $masternode->toArray(),
            'user' => $this->user->toArray()
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