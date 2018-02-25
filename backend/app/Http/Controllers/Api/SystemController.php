<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\System\LoadWalletsRequest;
use App\Http\Controllers\Controller;
use App\Services\System\FreeWalletService;

class SystemController extends Controller
{
    /**
     *
     * @SWG\Post(
     *     path="/systems/wallets",
     *     summary="Load wallets",
     *     tags={"System"},
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
     *                  property="wallets",
     *                  type="integer",
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
}
