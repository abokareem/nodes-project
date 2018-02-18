<?php

namespace App\Http\Controllers\Api;

use App\Currency;
use App\Events\AcceptedPutMoney;
use App\Events\MoneyWithdrawn;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AcceptPutMoneyRequest;
use App\Http\Requests\Api\PutMoneyRequest;
use App\Http\Requests\Api\WithdrawalMoneyRequest;
use App\Http\Resources\UserBillsResource;
use App\Services\UserBillService;
use App\User;

class UserBillController extends Controller
{
    /**
     *
     * @SWG\Post(
     *     path="/money",
     *     summary="Create or take bill",
     *     tags={"Bills"},
     *     description="Create or take bill",
     *     operationId="createBill",
     *     security={
     *         {
     *             "Bearer": {}
     *         }
     *     },
     *     @SWG\Parameter(
     *          name="bill",
     *          in="body",
     *          required=true,
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="currency_id",
     *                  type="integer",
     *                  description="currency id",
     *                  example=1
     *              ),
     *          ),
     *     ),
     *
     *     @SWG\Response(
     *      response=201,
     *      description="bill object",
     *      @SWG\Schema(
     *       title="Result",
     *       @SWG\Property(
     *        property="data",
     *        ref="#/definitions/UserBills"
     *       )
     *      )
     *     ),
     *
     *     @SWG\Response(
     *         response=422,
     *         description="bill",
     *         examples={
     *           "application/json":{
     *             "message": "The given data was invalid",
     *             "errors":{
     *                 "currency_id": {"The currency_id field is required."},
     *             },
     *           },
     *         },
     *     ),
     * )
     *
     * @param PutMoneyRequest $request
     * @param UserBillService $billService
     * @return UserBillsResource
     */
    public function putMoney(PutMoneyRequest $request, UserBillService $billService)
    {
        $currency = Currency::findOrFail($request->get('currency_id'));
        $wallet = $billService->put($currency);

        return new UserBillsResource($wallet);
    }

    /**
     *
     * @SWG\Post(
     *     path="/money/accept",
     *     summary="accept transaction",
     *     tags={"Bills"},
     *     description="accept transaction",
     *     operationId="acceptTransaction",
     *     security={
     *         {
     *             "Bearer": {}
     *         }
     *     },
     *     @SWG\Parameter(
     *          name="bill",
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
     *                  property="user_id",
     *                  type="integer",
     *                  description="user id",
     *                  example=2
     *              ),
     *              @SWG\Property(
     *                  property="amount",
     *                  type="integer",
     *                  description="amount",
     *                  example=123
     *              ),
     *          ),
     *     ),
     *
     *     @SWG\Response(
     *      response=201,
     *      description="bill object"
     *     ),
     *
     *     @SWG\Response(
     *         response=422,
     *         description="bill",
     *         examples={
     *           "application/json":{
     *             "message": "The given data was invalid",
     *             "errors":{
     *                 "currency_id": {"The currency_id field is required."},
     *             },
     *           },
     *         },
     *     ),
     * )
     *
     * @param AcceptPutMoneyRequest $request
     * @param UserBillService $billService
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function acceptPutMoney(AcceptPutMoneyRequest $request, UserBillService $billService)
    {
        $currency = Currency::findOrFail($request->get('currency_id'));
        $user = User::findOrFail($request->get('user_id'));
        $amount = $request->get('amount');

        $billService->acceptPut($currency, $user, $amount);

        event(new AcceptedPutMoney());

        return response('', 201);
    }

    /**
     *
     * @SWG\Delete(
     *     path="/money",
     *     summary="withdrawal money",
     *     tags={"Bills"},
     *     description="withdrawal money",
     *     operationId="withdrawalMoney",
     *     security={
     *         {
     *             "Bearer": {}
     *         }
     *     },
     *     @SWG\Parameter(
     *          name="bill",
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
     *                  property="price",
     *                  type="integer",
     *                  description="price",
     *                  example=123
     *              ),
     *          ),
     *     ),
     *
     *     @SWG\Response(
     *      response=201,
     *      description="bill object",
     *      @SWG\Schema(
     *       title="Result",
     *       @SWG\Property(
     *        property="data",
     *        ref="#/definitions/UserBills"
     *       )
     *      )
     *     ),
     *
     *     @SWG\Response(
     *         response=422,
     *         description="bill",
     *         examples={
     *           "application/json":{
     *             "message": "The given data was invalid",
     *             "errors":{
     *                 "currency_id": {"The currency_id field is required."},
     *             },
     *           },
     *         },
     *     ),
     * )
     *
     * @param WithdrawalMoneyRequest $request
     * @param UserBillService $billService
     * @return UserBillsResource
     */
    public function withdrawalMoney(WithdrawalMoneyRequest $request, UserBillService $billService)
    {
        $currency = Currency::findOrFail($request->get('currency_id'));
        $price = $request->get('price');

        $wallet = $billService->withdrawalMoney($currency, $price);

        event(new MoneyWithdrawn());

        return new UserBillsResource($wallet);
    }
}
