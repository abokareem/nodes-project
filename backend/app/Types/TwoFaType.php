<?php

namespace App\Types;

/**
 * Class TwoFaType
 * @package App\Types
 */
class TwoFaType
{
    private $qrCode;
    private $secretKey;
    private $reserveCode;

    /**
     * TwoFaType constructor.
     * @param string|null $qrCode
     * @param string $secretKey
     * @param string $reserveCode
     */
    public function __construct(string $qrCode = null, string $secretKey, string $reserveCode)
    {
        $this->qrCode = $qrCode;
        $this->secretKey = $secretKey;
        $this->reserveCode = $reserveCode;
    }

    /**
     * @return string
     */
    public function getSecretKey() : string
    {
        return $this->secretKey;
    }

    /**
     * @param string $secretKey
     */
    public function setSecretKey(string $secretKey)
    {
        $this->secretKey = $secretKey;
    }

    /**
     * @return string
     */
    public function getQrCode() : string
    {
        return $this->qrCode;
    }

    /**
     * @param string $qrCode
     */
    public function setQrCode(string $qrCode)
    {
        $this->qrCode = $qrCode;
    }

    /**
     * @return string
     */
    public function getReserveCode() : string
    {
        return $this->reserveCode;
    }

    /**
     * @param string $reserveCode
     */
    public function setReserveCode(string $reserveCode)
    {
        $this->reserveCode = $reserveCode;
    }
}
