<?php

namespace App\Services;

use App\Exceptions\TwoFaSecretNotExists;
use Illuminate\Support\Facades\Auth;

class TwoFaService
{
    /*public function enable()
    {
        $user = Auth::user();

        $user->google2fa_secret = $secretKey;
        $user->two_fa = true;
        $user->save();
    }

    public function generate($email)
    {
        $secretKey = Google2FA::generateSecretKey();

        $imageDataUri = Google2FA::getQRCodeInline(
            config('app.url'),
            $email,
            $secretKey,
            200
        );
        return
    }*/

    public function isEnable($user)
    {
        if ($user->two_fa) {

            if (!$user->google2fa_secret) {

                throw new TwoFaSecretNotExists();
            }

            return true;
        }

        return false;
    }
}