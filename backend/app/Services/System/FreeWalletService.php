<?php

namespace App\Services\System;

use App\Currency;
use App\Exceptions\System\FreeWalletsLengthException;
use App\Http\Requests\Api\System\LoadWalletsRequest;

/**
 * Class FreeWalletService
 * @package App\Services\System
 */
class FreeWalletService
{
    /**
     * @param LoadWalletsRequest $request
     * @throws FreeWalletsLengthException
     */
    public function load(LoadWalletsRequest $request)
    {
        $wallets = explode(PHP_EOL, $request->get('wallets'));
        $cleanWallets = [];

        foreach ($wallets as $wallet) {

            $clear = str_replace(' ', '', trim($wallet));

            if (mb_strlen($clear) < config('admin.wallet_length')) {
                throw new FreeWalletsLengthException();
            }

            $cleanWallets[] = [
                'hash' => $clear
            ];
        }

        $currency = Currency::findOrFail($request->get('currency_id'));
        $currency->freeWallets()->createMany($cleanWallets);
    }
}