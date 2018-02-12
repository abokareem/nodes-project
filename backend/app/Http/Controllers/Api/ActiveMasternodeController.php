<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\ActivateMasternodeRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ActiveMasternodeController extends Controller
{
    public function activate(ActivateMasternodeRequest $request)
    {
        $masternode = $request->get('currency_id');

    }
}
