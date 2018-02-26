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
    const ZERO = 0;

    public function __construct(Masternode $node)
    {
        $this->node = $node;
    }

    public function getInvestorsByAmount(string $amount)
    {
        $investors = $this->node->investments()
            ->orderBy('created_at', 'desc')->get();

        foreach ($investors as $investor) {
            if ($amount === self::ZERO) {
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

    public function getTransferAmount(array $investors)
    {
        if (!isset($this->transferInvestors)) {
            return self::ZERO;
        }
        $math = app(MathInterface::class);
        $amount = 0;

        foreach ($investors as $investor) {
            $amount = $math->add($amount, $investor->amount);
        }

        return $amount;
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

        return self::ZERO;
    }

    protected function less($amount, $investor)
    {
        $math = app(MathInterface::class);

        $investor->amount = $math->sub($investor->amount, $amount);
        $investor->save();

        $investor->amount = $amount;
        $this->transferInvestors[] = $investor;

        return self::ZERO;
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