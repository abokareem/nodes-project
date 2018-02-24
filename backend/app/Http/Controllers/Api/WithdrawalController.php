<?php

namespace App\Http\Controllers\Api;

use App\Events\AcceptedLeaveFromNode;
use App\Events\BoughtShares;
use App\Events\CreatedWithdrawal;
use App\Events\DeclinedWithdrawal;
use App\Exceptions\WithdrawalAlreadyNotProcessing;
use App\Http\Requests\Api\LeaveNodeRequest;
use App\Http\Resources\MessageResource;
use App\Masternode;
use App\Services\WithdrawalService;
use App\Http\Controllers\Controller;
use App\Withdrawals;
use Illuminate\Support\Facades\Auth;

class WithdrawalController extends Controller
{
    /**
     *
     * @SWG\Post(
     *     path="/withdrawals",
     *     summary="Create withdrawal",
     *     tags={"Withdrawal"},
     *     description="Create withdrawal",
     *     operationId="createWithdrawal",
     *     security={
     *         {
     *             "Bearer": {}
     *         }
     *     },
     *     @SWG\Parameter(
     *          name="withdrawal",
     *          in="body",
     *          required=true,
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="node_id",
     *                  type="integer",
     *                  description="masternode id",
     *                  example=5
     *              ),
     *          ),
     *     ),
     *
     *     @SWG\Response(
     *      response=201,
     *      description="Created withdrawal",
     *      examples={
     *           "application/json":{
     *             "message": "Withdrawal created.",
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
     *                 "node_id": {"The node_id field is required."},
     *             },
     *           },
     *         },
     *     ),
     * )
     *
     * @param LeaveNodeRequest $request
     * @param WithdrawalService $withdrawalService
     * @return MessageResource
     */
    public function store(LeaveNodeRequest $request, WithdrawalService $withdrawalService)
    {
        $node = Masternode::findOrFail($request->get('node_id'));

        $withdrawalService->out($node);

        event(new CreatedWithdrawal());

        return new MessageResource(trans('masternode.withdrawal.out'));
    }

    /**
     *
     * @SWG\Delete(
     *     path="/withdrawals/decline/{withdrawal}",
     *     summary="Decline withdrawal",
     *     tags={"Withdrawal"},
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
     * @param Withdrawals $withdrawal
     * @return MessageResource
     * @throws WithdrawalAlreadyNotProcessing
     */
    public function decline(Withdrawals $withdrawal)
    {

        if ($withdrawal->state !== Withdrawals::PROCESSING_STATE) {
            throw new WithdrawalAlreadyNotProcessing();
        }

        $withdrawal->update([
            'state' => Withdrawals::DECLINE_STATE
        ]);

        event(new DeclinedWithdrawal());

        return new MessageResource(trans('masternode.withdrawal.decline'));
    }

    /**
     *
     * @SWG\Patch(
     *     path="/withdrawals/approve/{withdrawal}",
     *     summary="Approve withdrawal",
     *     tags={"Withdrawal"},
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
     * @param Withdrawals $withdrawal
     * @param WithdrawalService $service
     * @return MessageResource
     */
    public function approve(Withdrawals $withdrawal, WithdrawalService $service)
    {
        $service->approve($withdrawal);

        event(new AcceptedLeaveFromNode(Auth::user()));

        return new MessageResource(trans('masternode.withdrawal.approve'));
    }

    /**
     *
     * @SWG\Post(
     *     path="/withdrawals/buy/{withdrawal}",
     *     summary="Buy withdrawal",
     *     tags={"Withdrawal"},
     *     description="Buy withdrawal",
     *     operationId="buyWithdrawals",
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
     *         description="withdrawals",
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
     * @param Withdrawals $withdrawal
     * @param WithdrawalService $service
     * @return MessageResource
     */
    public function buy(Withdrawals $withdrawal, WithdrawalService $service)
    {
        $service->buy($withdrawal);

        event(new AcceptedLeaveFromNode($withdrawal->user));
        event(new BoughtShares());

        return new MessageResource(trans('monetary.share.buy'));
    }
}
