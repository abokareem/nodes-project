<?php

namespace App\Services\Math;

use App\Exceptions\BCMathNotInstalledException;

/**
 * Class BCMathService
 * @package App\Services\Math
 */
class BCMathService implements MathInterface
{
    /**
     * BCMathService constructor.
     * @throws BCMathNotInstalledException
     */
    public function __construct()
    {
        if (!extension_loaded('bcmath')) {
            throw new BCMathNotInstalledException();
        }
    }

    /**
     * @param string $left
     * @param string $right
     * @return string
     */
    public function add(string $left, string $right): string
    {
        $scale = $this->getScale($left, $right);

        return bcadd($left, $right, $scale);
    }

    /**
     * @param string $left
     * @param string $right
     * @return string
     */
    public function multiply(string $left, string $right): string
    {
        $scale = $this->getScale($left, $right);

        return bcmul($left, $right, $scale);
    }

    /**
     * @param string $left
     * @param string $right
     * @return string
     */
    public function divide(string $left, string $right): string
    {
        $scale = $this->getScale($left, $right);

        return bcdiv($left, $right, $scale);
    }

    /**
     * @param string $left
     * @param string $right
     * @return string
     */
    public function sub(string $left, string $right): string
    {
        $scale = $this->getScale($left, $right);

        return bcsub($left, $right, $scale);
    }

    /**
     * @param string $left
     * @param string $right
     * @return string
     */
    public function comparison(string $left, string $right): string
    {
        $scale = $this->getScale($left, $right);

        return bccomp($left, $right, $scale);
    }

    /**
     * @param string $left
     * @param string $right
     * @return int
     */
    private function getScale(string $left, string $right): int
    {
        $leftCount = $this->getDecimalCount($left);
        $rightCount = $this->getDecimalCount($right);
        $scale = $leftCount > $rightCount ? $leftCount : $rightCount;

        return $scale;
    }

    /**
     * @param $number
     * @return int
     */
    private function getDecimalCount(string $number): int
    {
        if (substr_count($number, '.')) {
            list(, $decimals) = explode('.', $number);

            return strlen($decimals);
        }

        return 0;
    }
}