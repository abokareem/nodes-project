<?php

namespace App\Http\Controllers\Api;

use App\Events\BoughtShares;
use App\Events\MasternodeReadyToCreate;
use App\Http\Requests\Api\BuySharesRequest;
use App\Http\Resources\MessageResource;
use App\Http\Controllers\Controller;
use App\Masternode;
use App\Services\ShareService;

class ShareController extends Controller
{
    /**
     *
     * @SWG\Post(
     *     path="/shares/buy",
     *     summary="Buy shares",
     *     tags={"Shares"},
     *     description="Buy shares",
     *     operationId="buyShares",
     *     security={
     *         {
     *             "Bearer": {}
     *         }
     *     },
     *     @SWG\Parameter(
     *          name="node",
     *          in="body",
     *          required=true,
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="node_id",
     *                  type="integer",
     *                  description="",
     *                  example=1,
     *              ),
     *              @SWG\Property(
     *                  property="count",
     *                  type="integer",
     *                  description="",
     *                  example=3,
     *              ),
     *          ),
     *     ),
     *
     *     @SWG\Response(
     *      response=200,
     *      description="Shares bought",
     *      examples={
     *           "application/json":{
     *             "message": "You have acquired a stake in the masternode.",
     *           },
     *         },
     *     ),
     *
     *     @SWG\Response(
     *         response=422,
     *         description="shares",
     *         examples={
     *           "application/json":{
     *             "message": "The given data was invalid",
     *             "errors":{
     *                 "share_id": {"The share_id field is required."},
     *             },
     *           },
     *         },
     *     ),
     * )
     *
     * @param BuySharesRequest $request
     * @param ShareService $shareService
     * @return MessageResource
     * @throws \App\Exceptions\InsolventException
     */
    public function buy(BuySharesRequest $request, ShareService $shareService)
    {
        $count = $request->get('count');
        $node = Masternode::findOrFail($request->get('node_id'));

        $shareService->buy($node, $count);

        if ($node->price === $node->bill->amount) {
            event(new MasternodeReadyToCreate($node));
        }

        event(new BoughtShares());

        return new MessageResource(trans('monetary.share.buy'));
    }
}
