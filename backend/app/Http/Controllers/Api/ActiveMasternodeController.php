<?php

namespace App\Http\Controllers\Api;

use App\ActiveMasternode;
use App\Currency;
use App\Http\Requests\Api\ActivateMasternodeRequest;
use App\Masternode;
use App\Http\Controllers\Controller;
use App\Services\ActiveNodeService;

class ActiveMasternodeController extends Controller
{
    public function activate(ActivateMasternodeRequest $request, ActiveNodeService $nodeService)
    {
        $currency = Currency::findOrFail($request->get('currency_id'));

        $nodeService->create($currency, $request->get('type'));

        return response('good',200);
    }
}
