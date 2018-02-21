<?php

namespace App\Services\Settlement;

use App\Investment;
use App\Masternode;
use App\Services\Math\MathInterface;

class SecondaryNodeService
{
    private $investorsForDelete;
    private $transferInvestors;
    private $node;

    public function __construct(Masternode $node)
    {
        $this->node = $node;
    }

    public function getInvestorsByAmount(string $amount)
    {
        $investors = $this->node->investments()
            ->orderBy('created_at', 'desc')->get();

        foreach ($investors as $investor) {
            if ($amount === 0) {
                break;
            }
            $handler = $this->getHandle($amount, $investor->amount);
            $amount = $this->{$handler}($amount, $investor);
        }

        return $this->transferInvestors;
    }

    public function deleteGivenInvestors()
    {
        if ($this->investorsForDelete) {
            Investment::whereIn('id', $this->investorsForDelete)->delete();
        }
    }

    protected function getHandle(string $needed, string $amount)
    {
        $math = app(MathInterface::class);

        switch ((int)$math->comparison($needed, $amount)) {
            case MathInterface::EQUAL:

                return 'equal';
            case MathInterface::LESS:

                return 'less';
            case MathInterface::LARGE:

                return 'large';
        }
    }

    protected function equal($amount, $investor)
    {
        $this->transferInvestors[] = $investor;
        $this->investorsForDelete[] = $investor->id;

        return 0;
    }

    protected function less($amount, $investor)
    {
        $investor->amount = $amount;
        $this->transferInvestors[] = $investor;

        return 0;
    }

    protected function large($amount, $investor)
    {
        $math = app(MathInterface::class);

        $this->transferInvestors[] = $investor;
        $this->investorsForDelete[] = $investor->id;

        $amount = $math->sub($amount, $investor->amount);

        return $amount;
    }
}