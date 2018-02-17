<?php

namespace App\Http\Controllers\Api;

use App\Currency;
use App\Events\MasternodeCreated;
use App\Http\Requests\Api\CreateMasternodeRequest;
use App\Http\Requests\Api\UpdateMasternodeRequest;
use App\Http\Resources\MasternodeResource;
use App\Http\Resources\MessageResource;
use App\Masternode;
use App\Http\Controllers\Controller;
use App\Services\NodeService;

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
     *                  property="currency_id",
     *                  type="integer",
     *                  description="currency id",
     *                  example=1
     *              ),
     *              @SWG\Property(
     *                  property="type",
     *                  type="string",
     *                  description="masternode type",
     *                  example="single",
     *              ),
     *              @SWG\Property(
     *                  property="count",
     *                  type="integer",
     *                  description="shares count",
     *                  example=5,
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
     *                 "type": {"The type field is required."},
     *             },
     *           },
     *         },
     *     ),
     * )
     *
     * @param CreateMasternodeRequest $request
     * @param NodeService $nodeService
     * @return MessageResource
     *
     */
    public function store(CreateMasternodeRequest $request, NodeService $nodeService)
    {

        $currency = Currency::findOrFail($request->get('currency_id'));

        $nodeService->create($currency);

        event(new MasternodeCreated());

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
     *                     "type"="single",
     *                     "state"="new",
     *                     "price"=10
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
     *                 "price": {"The price field must be numeric."},
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
        $request = $request->only('state', 'type', 'price');
        $node->update($request);

        return new MessageResource(trans('masternode.update'));
    }
}
