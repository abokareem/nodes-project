<?php

namespace App\Types;

use App\ActiveMasternodeShares;
use App\Masternode;
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
    private $node;

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
    public function getNode()
    {
        if (isset($this->node)) {

            return $this->node;
        }

        $this->generateException();
    }

    /**
     * @param Masternode $node
     */
    public function setNode(Masternode $node)
    {
        $this->node = $node;
    }

    /**
     * @throws \Exception
     */
    protected function generateException()
    {
        throw new \Exception('Params for ShareBuyType not set', 500);
    }
}