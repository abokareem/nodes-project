<?php

namespace App\Types;

use App\Investment;
use App\Masternode;
use App\User;

/**
 * Class SettlementType
 * @package App\Types
 */
class SettlementType
{
    /**
     * @var
     */
    private $user;
    /**
     * @var
     */
    private $mainNode;
    /**
     * @var
     */
    private $secondaryNode;
    /**
     * @var
     */
    private $investment;

    /**
     * @param Masternode $node
     */
    public function setMainNode(Masternode $node)
    {
        $this->mainNode = $node;
    }

    /**
     * @return Masternode
     */
    public function getMainNode(): Masternode
    {
        return $this->mainNode;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param Masternode $node
     */
    public function setSecondaryNode(Masternode $node)
    {
        $this->secondaryNode = $node;
    }

    /**
     * @return Masternode
     */
    public function getSecondaryNode(): Masternode
    {
        return $this->secondaryNode;
    }

    /**
     * @param Investment $investment
     */
    public function setInvestment(Investment $investment)
    {
        $this->investment = $investment;
    }

    /**
     * @return Investment
     */
    public function getInvestment(): Investment
    {
        return $this->investment;
    }
}