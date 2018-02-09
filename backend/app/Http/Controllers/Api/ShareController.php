<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\BuySharesRequest;
use App\MasternodeShare;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ShareController extends Controller
{
    public function buy(BuySharesRequest $request)
    {
        $count = $request->get('share_count');

        $share = MasternodeShare::findOrFail($request->get('share_id'));

        $price = bcmul($share->price, $count);
        dd($price);
        $currency = $share->masternode->bill->currency;
        $user = Auth::user();

        $userBill = $user->bills()->where('currency_id', $currency->id)->firstOrFail();

        $priceNumbers = mb_strlen(mb_stristr($price, '.'));
        $userBillNumbers = mb_strlen(mb_stristr($userBill->amount, '.'));
        $numbers = $priceNumbers > $userBillNumbers ? $priceNumbers : $userBillNumbers;

        if (bccomp($price, $userBill->amount, $numbers) === 1) {
            throw new \Exception('netu babok');
        }
    }
}
