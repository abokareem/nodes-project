<?php

namespace App\Services\Math;

interface MathInterface
{
    const EQUAL = 0;
    const LARGE = 1;
    const LESS = -1;

    /**
     * @param string $left
     * @param string $right
     * @return string
     */
    public function add(string $left, string $right): string;

    /**
     * @param string $left
     * @param string $right
     * @return string
     */
    public function multiply(string $left, string $right): string;

    /**
     * @param string $left
     * @param string $right
     * @return string
     */
    public function divide(string $left, string $right): string;

    /**
     * @param string $left
     * @param string $right
     * @return string
     */
    public function sub(string $left, string $right): string;

    /**
     * @param string $left
     * @param string $right
     * @return string
     */
    public function comparison(string $left, string $right): string;
}