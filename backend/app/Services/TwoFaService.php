<?php

namespace App\Services;

use App\Exceptions\TwoFaSecretNotExists;
use App\Types\TwoFaType;
use App\User;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;

/**
 * Class TwoFaService
 * @package App\Services
 */
class TwoFaService
{
    private $service;

    /**
     * TwoFaService constructor.
     * @param Google2FA $google2FA
     */
    public function __construct(Google2FA $google2FA)
    {
        $this->service = $google2FA;
    }

    /**
     * @return TwoFaType
     */
    public function enable(): TwoFaType
    {
        $user = Auth::user();

        $secretKey = $this->generateKey();

        $user->google2fa_secret = $secretKey;
        $user->two_fa = true;
        $user->save();

        $qrCode = $this->generateQrCode($user->email, $secretKey);

        return new TwoFaType($qrCode, $secretKey);
    }

    /**
     * @param string $email
     * @param string $secret
     * @return string
     */
    protected function generateQrCode(string $email, string $secret): string
    {
        return Google2FA::getQRCodeInline(
            config('app.url'),
            $email,
            $secret,
            200
        );
    }

    /**
     * @return string
     */
    protected function generateKey(): string
    {
        return Google2FA::generateSecretKey();
    }

    /**
     * @param User $user
     * @return bool
     * @throws TwoFaSecretNotExists
     */
    public function isEnable(User $user): bool
    {
        if ($user->two_fa) {

            if (!$user->google2fa_secret) {

                throw new TwoFaSecretNotExists();
            }

            return true;
        }

        return false;
    }

    /**
     * @param string $secret
     * @param string $code
     * @return bool
     */
    public function verifyCode(string $secret, string $code): bool
    {
        return $this->service->verifyKey($secret, $code);
    }
}