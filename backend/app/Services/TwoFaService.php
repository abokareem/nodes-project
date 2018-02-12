<?php

namespace App\Services;

use App\Exceptions\TwoFaReserveCodeTries;
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
    /**
     * @var Google2FA
     */
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
     * @param string $hash
     * @param string $code
     * @param string $reserveCode
     * @return bool
     */
    public function enable(string $hash, string $code, string $reserveCode): bool
    {
        $user = Auth::user();

        $isValid = $this->verifyCode($hash, $code);

        if ($isValid) {

            $user->google2fa_secret = $hash;
            $user->two_fa = true;
            $user->save();

            $user->reserveTwoFa()->create([
                'code' => $reserveCode,
            ]);

            return true;
        }

        return false;
    }

    /**
     * @return TwoFaType
     */
    public function generateDataForActivate(): TwoFaType
    {
        $user = Auth::user();

        $secretKey = $this->generateKey();

        $qrCode = $this->generateQrCode($user->email, $secretKey);

        $reserveCode = str_random(8);

        return new TwoFaType($qrCode, $secretKey, $reserveCode);
    }

    /**
     * @param string $code
     * @return bool
     */
    public function disable(string $code): bool
    {
        $user = Auth::user();

        $isValid = $this->verifyCode($user->google2fa_secret, $code);

        if ($isValid) {

            return $this->disableTwoFa($user);
        }

        return false;
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

    /**
     * @param string $email
     * @param string $reserveCode
     * @return bool
     * @throws TwoFaReserveCodeTries
     */
    public function resetTwoFa(string $email, string $reserveCode)
    {
        $user = User::where('email', $email)->firstOrFail();

        $reserveModel = $user->reserveTwoFa()->firstOrFail();

        if ($reserveModel->code === $reserveCode) {

            return $this->disableTwoFa($user);
        }

        $reserveModel->tries = $reserveModel->tries + 1;

        $reserveModel->save();

        if ($reserveModel->tries === 3) {

            $user->delete();

            throw new TwoFaReserveCodeTries();
        }

        return false;
    }

    /**
     * @param User $user
     * @return bool
     */
    protected function disableTwoFa(User $user)
    {
        $user->google2fa_secret = null;
        $user->two_fa = false;
        $user->save();

        $user->reserveTwoFa()->delete();

        return true;
    }

    /**
     * @param string $email
     * @param string $secret
     * @return string
     */
    protected function generateQrCode(string $email, string $secret): string
    {
        return $this->service->getQRCodeInline(
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
        return $this->service->generateSecretKey();
    }
}