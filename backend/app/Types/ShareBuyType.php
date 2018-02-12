<?php

namespace App\Types;

use App\ActiveMasternodeShares;
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
     * @throws \Exception
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
     * @throws \Exception
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
     * @throws \Exception
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
     * @throws \Exception
     */
    public function getShare()
    {
        if (isset($this->share)) {

            return $this->share;
        }

        $this->generateException();
    }

    /**
     * @param ActiveMasternodeShares $share
     */
    public function setShare(ActiveMasternodeShares $share)
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