<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\CreateMasternodeRequest;
use App\Http\Requests\Api\UpdateMasternodeRequest;
use App\Http\Resources\MasternodeResource;
use App\Http\Resources\MessageResource;
use App\Masternode;
use App\Http\Controllers\Controller;

class MasternodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @SWG\Get(
     *     path="/nodes",
     *     summary="Get masternodes",
     *     tags={"Masternode"},
     *     description="List of masternodes",
     *     operationId="getMasternodes",
     *     @SWG\Response(
     *      response=200,
     *      description="Masternode objects",
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
        return MasternodeResource::collection(Masternode::paginate());
    }

    /**
     *
     * Store a newly created resource in storage.
     *
     *
     * @SWG\Post(
     *     path="/nodes",
     *     summary="Create masternode",
     *     tags={"Masternode"},
     *     description="Create masternode",
     *     operationId="createMasternode",
     *     security={
     *         {
     *             "Bearer": {}
     *         }
     *     },
     *     @SWG\Parameter(
     *          name="masternode",
     *          in="body",
     *          required=true,
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="masternode",
     *                  type="object",
     *                  description="masternode object",
     *                  example={
     *                     "currency_id"=1,
     *                     "name"="test",
     *                     "description"="test description",
     *                     "income"="0.1",
     *                     "price"=10
     *                  }
     *              ),
     *              @SWG\Property(
     *                  property="share",
     *                  type="object",
     *                  description="share object",
     *                  example={
     *                      "price"=10,
     *                      "count"=5
     *                  }
     *              ),
     *          ),
     *     ),
     *
     *     @SWG\Response(
     *      response=201,
     *      description="Created masternode",
     *      examples={
     *           "application/json":{
     *             "message": "Masternode created.",
     *           },
     *         },
     *     ),
     *
     *     @SWG\Response(
     *         response=422,
     *         description="masternode",
     *         examples={
     *           "application/json":{
     *             "message": "The given data was invalid",
     *             "errors":{
     *                 "masternode.name": {"The masternode.name field is required."},
     *             },
     *           },
     *         },
     *     ),
     * )
     *
     * @param CreateMasternodeRequest $request
     * @return MessageResource
     *
     */
    public function store(CreateMasternodeRequest $request)
    {
        /*$newNode = Masternode::create($request->get('masternode'));
        $newNode->share()->create($request->get('share'));*/

        return new MessageResource(trans('masternode.create'));
    }

    /**
     * Get the specified resource.
     *
     * @SWG\Get(
     *     path="/nodes/{node}",
     *     summary="Get Masternode",
     *     tags={"Masternode"},
     *     description="One masternode",
     *     operationId="getMasternode",
     *     @SWG\Parameter(
     *          name="node",
     *          in="path",
     *          type="integer",
     *          required=true
     *      ),
     *     @SWG\Response(
     *      response=200,
     *      description="masternode object",
     *      @SWG\Schema(
     *       title="Result",
     *       @SWG\Property(
     *        property="data",
     *        ref="#/definitions/Masternode"
     *       )
     *      )
     *     ),
     * )
     *
     * @param Masternode $node
     * @return MasternodeResource
     */
    public function show(Masternode $node)
    {
        return new MasternodeResource($node);
    }

    /**
     * Update the specified resource in storage.
     *
     * @SWG\Patch(
     *     path="/nodes/{node}",
     *     summary="Update masternode",
     *     tags={"Masternode"},
     *     description="Update masternode",
     *     operationId="updateMasternode",
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
     *          name="masternode",
     *          in="body",
     *          required=true,
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="masternode",
     *                  type="object",
     *                  description="masternode object",
     *                  example={
     *                     "currency_id"=1,
     *                     "name"="test",
     *                     "description"="test description",
     *                     "income"="0.1",
     *                     "price"=10
     *                  }
     *              ),
     *              @SWG\Property(
     *                  property="share",
     *                  type="object",
     *                  description="share object",
     *                  example={
     *                      "price"=10,
     *                      "count"=5
     *                  }
     *              ),
     *          ),
     *     ),
     *
     *     @SWG\Response(
     *      response=200,
     *      description="Updated masternode",
     *      examples={
     *           "application/json":{
     *             "message": "Masternode updated.",
     *           },
     *         },
     *     ),
     *
     *     @SWG\Response(
     *         response=422,
     *         description="masternode",
     *         examples={
     *           "application/json":{
     *             "message": "The given data was invalid",
     *             "errors":{
     *                 "masternode.name": {"The masternode.name field is required."},
     *             },
     *           },
     *         },
     *     ),
     * )
     *
     * @param UpdateMasternodeRequest $request
     * @param Masternode $node
     * @return MessageResource
     */
    public function update(UpdateMasternodeRequest $request, Masternode $node)
    {
        $newData = $request->only(
            'masternode.price', 'masternode.income', 'masternode.description',
            'masternode.name', 'masternode.currency_id', 'share.price', 'share.count'
        );

        if ($request->has('masternode')) {
            $node->update($newData['masternode']);
        }
        if ($request->has('share')) {
            $node->share()->update($newData['share']);
        }

        return new MessageResource(trans('masternode.update'));
    }
}
