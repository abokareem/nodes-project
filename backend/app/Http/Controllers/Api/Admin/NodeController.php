<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Requests\Api\Admin\SetProfitRequest;
use App\Http\Resources\MasternodeResource;
use App\Http\Resources\MessageResource;
use App\Http\Resources\WithdrawalResource;
use App\Masternode;
use App\Services\NodeService;
use App\Http\Controllers\Controller;
use App\Withdrawals;

class NodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @SWG\Get(
     *     path="/admin/nodes",
     *     summary="Get nodes",
     *     tags={"Admin"},
     *     description="List of nodes",
     *     operationId="getNodes",
     *     security={
     *         {
     *             "Bearer": {}
     *         }
     *     },
     *     @SWG\Response(
     *      response=200,
     *      description="nodes objects",
     *      @SWG\Schema(
     *       title="Result",
     *       @SWG\Property(
     *        property="data",
     *        type="array",
     *        @SWG\Items(ref="#/definitions/Masternode")
     *       )
     *      )
     *     ),
     * )
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return MasternodeResource::collection(
            Masternode::withTrashed()->get()
        );
    }

    /**
     *
     * @SWG\Post(
     *     path="/admin/nodes/{node}/profits",
     *     summary="Set daily profit",
     *     tags={"Admin"},
     *     description="Set daily profit",
     *     operationId="dailyProfit",
     *     security={
     *         {
     *             "Bearer": {}
     *         }
     *     },
     *     @SWG\Parameter(
     *          name="node",
     *          in="path",
     *          type="integer",
     *          required=true
     *      ),
     *     @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          required=true,
     *          @SWG\Schema(
     *           @SWG\Property(
     *                  property="amount",
     *                  type="string",
     *                  description="",
     *                  example="20",
     *              ),
     *          ),
     *     ),
     *     @SWG\Response(
     *      response=200,
     *      description="Profit message",
     *      examples={
     *           "application/json":{
     *             "message": "Profit is distributed.",
     *           },
     *         },
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Token is expired or blacklisted.",
     *         examples={
     *                  "application/json":{
     *                              "message":"Unauthenticated."
     *                  },
     *         },
     *     )
     * )
     *
     * @param SetProfitRequest $request
     * @param Masternode $node
     * @return MessageResource
     */
    public function setProfit(SetProfitRequest $request, Masternode $node, NodeService $service)
    {
        $service->profit($request->get('amount'), $node);

        return new MessageResource(trans('masternode.profit'));
    }

    /**
     *
     * @SWG\Get(
     *     path="/admin/withdrawals",
     *     summary="Get withdrawals",
     *     tags={"Admin"},
     *     description="List of withdrawals",
     *     operationId="getWithdrawals",
     *     security={
     *         {
     *             "Bearer": {}
     *         }
     *     },
     *     @SWG\Response(
     *      response=200,
     *      description="withdrawals objects",
     *      @SWG\Schema(
     *       title="Result",
     *       @SWG\Property(
     *        property="data",
     *        type="array",
     *        @SWG\Items(ref="#/definitions/AdminNodeWithdrawal")
     *       )
     *      )
     *     ),
     * )
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getWithdrawals()
    {
        return WithdrawalResource::collection(
            Withdrawals::all()
        );
    }
}
