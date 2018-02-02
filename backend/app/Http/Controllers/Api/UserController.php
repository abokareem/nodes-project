<?php

namespace App\Http\Controllers\Api;

use App\EmailConfirmation;
use App\Http\Controllers\Controller;
use App\Http\Resources\TwoFaEnableResource;
use App\Http\Resources\UserResource;
use App\Services\TwoFaService;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     *
     * @SWG\Get(
     *     path="/users",
     *     summary="Get User",
     *     tags={"Users"},
     *     description="This request cannot be done without authorization.",
     *     operationId="getUser",
     *     security={
     *         {
     *             "Bearer": {}
     *         }
     *     },
     *     @SWG\Response(
     *      response=200,
     *      description="user object",
     *      @SWG\Schema(
     *       title="Result",
     *       @SWG\Property(
     *        property="data",
     *        ref="#/definitions/User"
     *       )
     *      )
     *     ),
     *     @SWG\Response(
     *         response=401,
     *         description="Token is expired or blacklisted.",
     *         examples={
     *           "application/json":{
     *               "message":"Unauthenticated"
     *           },
     *         },
     *     ),
     * )
     *
     *
     * @return UserResource
     *
     */
    public function show()
    {
        return new UserResource(Auth::user());
    }

    /**
     *
     * Confirm user email.
     *
     * @SWG\Get(
     *     path="/users/email/confirm/{token}",
     *     summary="Confirm email",
     *     tags={"Users"},
     *     description="Confirm user email.",
     *     operationId="confirmEmail",
     *     @SWG\Parameter(
     *          name="token",
     *          in="path",
     *          type="string",
     *          required=true
     *      ),
     *     @SWG\Response(
     *      response=200,
     *      description="Email confirmed",
     *      @SWG\Schema(
     *       title="Result",
     *       @SWG\Property(
     *        property="data",
     *        ref="#/definitions/User"
     *       )
     *      )
     *     ),
     * )
     *
     * @param EmailConfirmation $token
     * @return UserResource
     * @throws \Exception
     *
     */
    public function confirmEmail(EmailConfirmation $token)
    {
        $user = $token->user;
        $user->email_confirmed = $token::CONFIRMED;
        $user->save();

        $token->delete();

        return new UserResource($user);
    }

    public function enableTwoFa(TwoFaService $service2FA)
    {
        $type2FA = $service2FA->enable();
        return new TwoFaEnableResource($type2FA);
    }
}
