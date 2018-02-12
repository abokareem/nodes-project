<?php

namespace App\Types;

use App\MasternodeShare;
use App\UserBill;

/**
 * Class ShareBuyType
 * @package App\Types
 */
class ShareBuyType
{
    /**
     * @var
     */
    private $price;
    /**
     * @var
     */
    private $userBill;
    /**
     * @var
     */
    private $count;
    /**
     * @var
     */
    private $share;

    /**
     * @return mixed
     */
    public function getPrice()
    {
        if (isset($this->price)) {

            return $this->price;
        }

        $this->generateException();
    }

    /**
     * @param string $price
     */
    public function setPrice(string $price)
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getSharesCount()
    {
        if (isset($this->count)) {

            return $this->count;
        }

        $this->generateException();
    }

    /**
     * @param string $count
     */
    public function setSharesCount(string $count)
    {
        $this->count = $count;
    }

    /**
     * @return mixed
     */
    public function getUserBill()
    {
        if (isset($this->userBill)) {

            return $this->userBill;
        }

        $this->generateException();
    }

    /**
     * @param UserBill $bill
     */
    public function setUserBill(UserBill $bill)
    {
        $this->userBill = $bill;
    }

    /**
     * @return mixed
     */
    public function getShare()
    {
        if (isset($this->share)) {

            return $this->share;
        }

        $this->generateException();
    }

    /**
     * @param MasternodeShare $share
     */
    public function setShare(MasternodeShare $share)
    {
        $this->share = $share;
    }

    /**
     * @throws \Exception
     */
    protected function generateException()
    {
        throw new \Exception('Params for ShareBuyType not set', 500);
    }
}