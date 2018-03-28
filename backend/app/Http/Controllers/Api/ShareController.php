<?php

namespace App\Http\Controllers\Api;

use App\Events\BoughtShares;
use App\Events\MasternodeReadyToCreate;
use App\Http\Requests\Api\BuySharesRequest;
use App\Http\Requests\Api\UpdateShareRequest;
use App\Http\Resources\MasternodeShareResource;
use App\Http\Resources\MessageResource;
use App\Http\Controllers\Controller;
use App\Masternode;
use App\Services\ShareService;
use App\Share;

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

    /**
     * Update the specified resource in storage.
     *
     *
     * @SWG\Patch(
     *     path="/shares/{share}",
     *     summary="Update Share",
     *     tags={"Admin"},
     *     description="Update share",
     *     operationId="updateShare",
     *     security={
     *         {
     *             "Bearer": {}
     *         }
     *     },
     *     @SWG\Parameter(
     *          name="share",
     *          in="path",
     *          type="integer",
     *          required=true
     *      ),
     *     @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          required=true,
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="share_price",
     *                  type="number",
     *                  description="",
     *                  example="2",
     *              ),
     *              @SWG\Property(
     *                  property="full_price",
     *                  type="number",
     *                  description="",
     *                  example="1",
     *              ),
     *          ),
     *     ),
     *     @SWG\Response(
     *      response=200,
     *      description="Updated share",
     *      @SWG\Schema(
     *       title="Result",
     *       @SWG\Property(
     *        property="data",
     *        ref="#/definitions/MasternodeShares"
     *       )
     *      )
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Token is expired or blacklisted.",
     *         examples={
     *                  "application/json":{
     *                              "message":"Unauthenticated."
     *                  },
     *         },
     *     ),
     *     @SWG\Response(
     *         response="422",
     *         description="Provided data is invalid and can not be used (validator error)",
     *         examples={
     *                  "application/json":{
     *                          "message": "The given data was invalid",
     *                          "errors":{
     *                              "share_price":{"The last name must be a numeric."}
     *                          },
     *                  },
     *         },
     *     ),
     * )
     *
     * @param Share $share
     * @param UpdateShareRequest $request
     * @return MasternodeShareResource
     *
     */
    public function update(UpdateShareRequest $request, Share $share)
    {
        $data = $request->only(['share_price', 'full_price']);

        $share->update($data);

        return new MasternodeShareResource($share);
    }
}
