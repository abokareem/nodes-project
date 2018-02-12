<?php

namespace App\Http\Controllers\Api;

use App\Events\BoughtShares;
use App\Events\MasternodeReadyToCreate;
use App\Http\Requests\Api\BuySharesRequest;
use App\Http\Resources\MessageResource;
use App\MasternodeShare;
use App\Http\Controllers\Controller;
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
     *          name="shares",
     *          in="body",
     *          required=true,
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="share_id",
     *                  type="integer",
     *                  description="",
     *                  example=2,
     *              ),
     *              @SWG\Property(
     *                  property="share_count",
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
     */
    public function buy(BuySharesRequest $request, ShareService $shareService)
    {
        $count = $request->get('share_count');
        $share = MasternodeShare::findOrFail($request->get('share_id'));

        $shareService->buy($share, $count);

        event(new BoughtShares());
        event(new MasternodeReadyToCreate());

        return new MessageResource(trans('monetary.share.buy'));
    }
}
