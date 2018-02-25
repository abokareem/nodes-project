<?php

namespace App\Http\Controllers\Api;

use App\Currency;
use App\Events\AcceptedPutMoney;
use App\Events\MoneyWithdrawn;
use App\Exceptions\UserBillNotExist;
use App\Exceptions\WithdrawalMoneyAlreadyNotProcessing;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AcceptPutMoneyRequest;
use App\Http\Requests\Api\CreateUserBillRequest;
use App\Http\Requests\Api\WithdrawalMoneyRequest;
use App\Http\Resources\MessageResource;
use App\Http\Resources\UserBillsResource;
use App\Services\UserBillService;
use App\User;
use App\UserBill;
use App\WithdrawalMoney;

class UserBillController extends Controller
{
    /**
     *
     * @SWG\Post(
     *     path="/money",
     *     summary="Create bill",
     *     tags={"Bills"},
     *     description="Create bill",
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
     * @param CreateUserBillRequest $request
     * @param UserBillService $billService
     * @return UserBillsResource
     */
    public function store(CreateUserBillRequest $request, UserBillService $billService)
    {
        $currency = Currency::findOrFail($request->get('currency_id'));
        $bill = $billService->create($currency);
        return new UserBillsResource($bill);
    }

    /**
     *
     * @SWG\Get(
     *     path="/money/{currency}",
     *     summary="Take bill",
     *     tags={"Bills"},
     *     description="Take bill",
     *     operationId="takeBill",
     *     security={
     *         {
     *             "Bearer": {}
     *         }
     *     },
     *     @SWG\Parameter(
     *          name="currency",
     *          in="path",
     *          type="integer",
     *          required=true
     *      ),
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
     * @param Currency $currency
     * @param UserBillService $billService
     * @return UserBillsResource
     * @throws UserBillNotExist
     */
    public function getBill(Currency $currency, UserBillService $billService)
    {
        $wallet = $billService->put($currency);

        if (!$wallet) {
            throw new UserBillNotExist();
        }

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
     *     deprecated=true,
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
     *                  property="bill_id",
     *                  type="integer",
     *                  description="bill id",
     *                  example=1
     *              ),
     *              @SWG\Property(
     *                  property="wallet",
     *                  type="integer",
     *                  description="wallet hash",
     *                  example="some hash"
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
        $bill = UserBill::findOrFail($request->get('bill_id'));
        $price = $request->get('price') ?? $bill->amount;
        $externalWallet = $request->get('wallet');

        $wallet = $billService->withdrawalMoney($bill, $externalWallet, $price);

        event(new MoneyWithdrawn());

        return new UserBillsResource($wallet);
    }

    /**
     *
     * @SWG\Delete(
     *     path="/money/decline/{withdrawal}",
     *     summary="Decline withdrawal",
     *     tags={"Bills"},
     *     description="Decline withdrawal",
     *     operationId="declineWithdrawal",
     *     security={
     *         {
     *             "Bearer": {}
     *         }
     *     },
     *     @SWG\Parameter(
     *          name="withdrawal",
     *          in="path",
     *          type="integer",
     *          required=true
     *      ),
     *
     *     @SWG\Response(
     *      response=200,
     *      description="Declined withdrawal",
     *      examples={
     *           "application/json":{
     *             "message": "Withdrawal declined.",
     *           },
     *         },
     *     ),
     *
     *     @SWG\Response(
     *         response=422,
     *         description="withdrawal",
     *         examples={
     *           "application/json":{
     *             "message": "The given data was invalid",
     *             "errors":{
     *                 "withdrawal": {"The withdrawal field is required."},
     *             },
     *           },
     *         },
     *     ),
     * )
     *
     * @param WithdrawalMoney $withdrawal
     * @return MessageResource
     * @throws WithdrawalMoneyAlreadyNotProcessing
     */
    public function decline(WithdrawalMoney $withdrawal)
    {

        if ($withdrawal->state !== WithdrawalMoney::PROCESSING) {
            throw new WithdrawalMoneyAlreadyNotProcessing();
        }

        $withdrawal->update([
            'state' => WithdrawalMoney::DECLINE
        ]);

        return new MessageResource(trans('monetary.bill.withdrawal.decline'));
    }

    /**
     *
     * @SWG\Patch(
     *     path="/money/approve/{withdrawal}",
     *     summary="Approve withdrawal",
     *     tags={"Bills"},
     *     description="Approve withdrawal",
     *     operationId="approveWithdrawal",
     *     security={
     *         {
     *             "Bearer": {}
     *         }
     *     },
     *     @SWG\Parameter(
     *          name="withdrawal",
     *          in="path",
     *          type="integer",
     *          required=true
     *      ),
     *
     *     @SWG\Response(
     *      response=200,
     *      description="Approve withdrawal",
     *      examples={
     *           "application/json":{
     *             "message": "Withdrawal approve.",
     *           },
     *         },
     *     ),
     *
     *     @SWG\Response(
     *         response=422,
     *         description="withdrawal",
     *         examples={
     *           "application/json":{
     *             "message": "The given data was invalid",
     *             "errors":{
     *                 "withdrawal": {"The withdrawal field is required."},
     *             },
     *           },
     *         },
     *     ),
     * )
     *
     * @param WithdrawalMoney $withdrawal
     * @return MessageResource
     * @throws WithdrawalMoneyAlreadyNotProcessing
     */
    public function approve(WithdrawalMoney $withdrawal)
    {
        if ($withdrawal->state !== WithdrawalMoney::PROCESSING) {
            throw new WithdrawalMoneyAlreadyNotProcessing();
        }

        $withdrawal->update([
            'state' => WithdrawalMoney::APPROVE
        ]);

        return new MessageResource(trans('monetary.bill.withdrawal.accept'));
    }
}
