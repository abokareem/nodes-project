<?php

namespace App\Http\Controllers\Api;

use App\ActiveMasternode;
use App\Http\Requests\Api\ActivateMasternodeRequest;
use App\Masternode;
use App\Http\Controllers\Controller;

class ActiveMasternodeController extends Controller
{
    public function activate(ActivateMasternodeRequest $request)
    {
        $masternode = Masternode::where('currency_id', $request->get('currency_id'))
            ->firstOrFail();

        $activeNode = ActiveMasternode::create([
            'masternode_id' => $masternode->id,
            'state' => 'processing',
            'type' => $request->get('type')
        ]);

        $activeNode->share()->create([
            'price' => $masternode->share->price,
            'count' => $masternode->share->count
        ]);

        $activeNode->bill()->create([
            'currency_id' => $request->get('currency_id')
        ]);
    }
}
