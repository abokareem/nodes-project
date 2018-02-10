<?php

namespace App\Services\Math;

interface MathInterface
{
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