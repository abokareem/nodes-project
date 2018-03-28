<?php

namespace App\Http\Controllers\Api\Admin;

use App\Commission;
use App\Events\AcceptedPutMoney;
use App\Http\Requests\Api\System\UpdateUserBillRequest;
use App\Http\Resources\UserBillsResource;
use App\Services\Math\MathInterface;
use App\UserBill;
use App\Http\Controllers\Controller;

class UserBillController extends Controller
{
    /**
     *
     * @SWG\Get(
     *     path="/bills",
     *     summary="Get bills",
     *     tags={"Admin"},
     *     description="List of bills",
     *     operationId="getBills",
     *     security={
     *         {
     *             "Bearer": {}
     *         }
     *     },
     *     @SWG\Response(
     *      response=200,
     *      description="bills objects",
     *      @SWG\Schema(
     *       title="Result",
     *       @SWG\Property(
     *        property="data",
     *        type="array",
     *        @SWG\Items(ref="#/definitions/UserBills")
     *       )
     *      )
     *     ),
     * )
     *
     * @return UserBillsResource
     */
    public function index()
    {
        return UserBillsResource::collection(UserBill::paginate());
    }

    /**
     * Display the specified resource.
     *
     *
     * @SWG\Get(
     *     path="/bills/{bill}",
     *     summary="Get bill",
     *     tags={"Admin"},
     *     description="One bill",
     *     operationId="getBill",
     *     security={
     *         {
     *             "Bearer": {}
     *         }
     *     },
     *     @SWG\Parameter(
     *          name="bill",
     *          in="path",
     *          type="integer",
     *          required=true
     *      ),
     *     @SWG\Response(
     *      response=200,
     *      description="bill object",
     *      @SWG\Schema(
     *       title="Result",
     *       @SWG\Property(
     *        property="data",
     *        ref="#/definitions/UserBills"
     *       )
     *      )
     *     ),
     * )
     *
     * @param  UserBill $bill
     * @return \Illuminate\Http\Response
     */
    public function show(UserBill $bill)
    {
        return new UserBillsResource($bill);
    }

    /**
     * Update the specified resource in storage.
     *
     *
     * @SWG\Patch(
     *     path="/bills/{bill}",
     *     summary="Update bill",
     *     tags={"Admin"},
     *     description="Update bill",
     *     operationId="updateBill",
     *     security={
     *         {
     *             "Bearer": {}
     *         }
     *     },
     *     @SWG\Parameter(
     *          name="bill",
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
     *      description="Create currency",
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
     *                              "name":{"The last name must be at least 2 characters."}
     *                          },
     *                  },
     *         },
     *     ),
     * )
     *
     * @param  UpdateUserBillRequest $request
     * @param  UserBill $bill
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserBillRequest $request, UserBill $bill)
    {
        $amount = $request->get('amount');

        $math = app(MathInterface::class);
        $commission = Commission::where('type', Commission::REPLENISH)->firstOrFail();

        $percent = $math->percent($amount, $commission->percent);
        $bill->amount = $math->add($bill->amount, $math->sub($amount, $percent));
        $bill->save();

        event(new AcceptedPutMoney($bill->user));

        return new UserBillsResource($bill);
    }
}
