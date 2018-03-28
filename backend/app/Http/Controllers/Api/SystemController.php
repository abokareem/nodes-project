<?php

namespace App\Http\Controllers\Api;

use App\Commission;
use App\Http\Requests\Api\Admin\UpdateCommissionRequest;
use App\Http\Requests\Api\System\ContactUsRequest;
use App\Http\Requests\Api\System\LoadWalletsRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\SystemCommissionResource;
use App\Http\Resources\MessageResource;
use App\Mail\ContactUs;
use App\Services\System\FreeWalletService;
use Illuminate\Support\Facades\Mail;

class SystemController extends Controller
{
    /**
     *
     * @SWG\Post(
     *     path="/systems/wallets",
     *     summary="Load wallets",
     *     tags={"Admin"},
     *     description="Load wallets",
     *     operationId="loadWallet",
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
     *                  description="wallets hash",
     *                  example="1"
     *              ),
     *              @SWG\Property(
     *                  property="wallets",
     *                  type="string",
     *                  description="wallets hash",
     *                  example="asdqwx12xwadx234xasa"
     *              ),
     *          ),
     *     ),
     *
     *     @SWG\Response(
     *      response=201,
     *      description="bill object",
     *     ),
     *
     *     @SWG\Response(
     *         response=422,
     *         description="bill",
     *         examples={
     *           "application/json":{
     *             "message": "The given data was invalid",
     *             "errors":{
     *                 "wallets": {"The wallets field is required."},
     *             },
     *           },
     *         },
     *     ),
     * )
     *
     * @param LoadWalletsRequest $request
     * @param FreeWalletService $walletService
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function loadWallets(LoadWalletsRequest $request, FreeWalletService $walletService)
    {
        $walletService->load($request);
        return response('', 201);
    }

    /**
     *
     * @SWG\Get(
     *     path="/admin/commissions",
     *     summary="Get commissions",
     *     tags={"Admin"},
     *     description="List of commissions",
     *     operationId="getCommissions",
     *     security={
     *         {
     *             "Bearer": {}
     *         }
     *     },
     *     @SWG\Response(
     *      response=200,
     *      description="commissions objects",
     *      @SWG\Schema(
     *       title="Result",
     *       @SWG\Property(
     *        property="data",
     *        type="array",
     *        @SWG\Items(ref="#/definitions/Commissions")
     *       )
     *      )
     *     ),
     * )
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getCommissions()
    {
        return SystemCommissionResource::collection(
            Commission::all()
        );
    }

    /**
     *
     * @SWG\Patch(
     *     path="/admin/commissions/{commission}",
     *     summary="Update commissions",
     *     tags={"Admin"},
     *     description="Update commissions",
     *     operationId="updateCommissions",
     *     security={
     *         {
     *             "Bearer": {}
     *         }
     *     },
     *     @SWG\Parameter(
     *          name="commission",
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
     *                  property="percent",
     *                  type="string",
     *                  description="",
     *                  example="12",
     *              ),
     *          ),
     *     ),
     *     @SWG\Response(
     *      response=200,
     *      description="commissions objects",
     *      @SWG\Schema(
     *       title="Result",
     *       @SWG\Property(
     *        property="data",
     *        type="array",
     *        @SWG\Items(ref="#/definitions/Commissions")
     *       )
     *      )
     *     ),
     * )
     *
     * @param UpdateCommissionRequest $request
     * @param Commission $commission
     * @return SystemCommissionResource
     */
    public function updateCommissions(UpdateCommissionRequest $request, Commission $commission)
    {
        $percent = $request->only(['percent']);

        $commission->update($percent);

        return new SystemCommissionResource($commission);
    }

    /**
     *
     * @SWG\Post(
     *     path="/contact",
     *     summary="Contact us form",
     *     tags={"Admin"},
     *     description="Contact us form",
     *     operationId="contactUs",
     *     @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          required=true,
     *          @SWG\Schema(
     *           @SWG\Property(
     *                  property="name",
     *                  type="string",
     *                  description="",
     *                  example="first",
     *              ),
     *            @SWG\Property(
     *                  property="message",
     *                  type="string",
     *                  description="",
     *                  example="some message",
     *              ),
     *             @SWG\Property(
     *                  property="subject",
     *                  type="string",
     *                  description="",
     *                  example="some subject",
     *              ),
     *              @SWG\Property(
     *                  property="email",
     *                  type="string",
     *                  description="",
     *                  example="first@example.com",
     *              ),
     *          ),
     *     ),
     *     @SWG\Response(
     *      response=200,
     *      description="Contact us",
     *      examples={
     *           "application/json":{
     *             "message": "Message was sent.",
     *           },
     *         },
     *     ),
     * )
     *
     * @param ContactUsRequest $request
     * @return MessageResource
     */
    public function contactUs(ContactUsRequest $request)
    {
        Mail::to(config('admin.email'))->queue(new ContactUs($request));

        return new MessageResource(trans('mails.contactUs'));
    }
}
