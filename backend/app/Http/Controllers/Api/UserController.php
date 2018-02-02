<?php

namespace App\Http\Controllers\Api;

use App\EmailConfirmation;
use App\Events\TwoFaEnabled;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     *
     * @SWG\Get(
     *     path="/api/users",
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
     *     path="/api/users/email/confirm/{token}",
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

    public function enableTwoFa()
    {
        $secretKey = Google2FA::generateSecretKey();

        $user = Auth::user();
        $user->google2fa_secret = $secretKey;
        $user->save();

        $imageDataUri = Google2FA::getQRCodeInline(
            config('app.name'),
            $user->email,
            $secretKey,
            200
        );
        return
        event(new TwoFaEnabled(Auth::user()));
    }
    /**
     * @SWG\Post(
     *     path="/oauth/token",
     *     summary="Authenticate user",
     *     tags={"Users"},
     *     description="Authenticate user",
     *     operationId="createTokens",
     *     @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          required=true,
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="grant_type",
     *                  type="string",
     *                  description="type for auth",
     *                  example="password",
     *              ),
     *              @SWG\Property(
     *                  property="client_id",
     *                  type="int",
     *                  description="client id in api db",
     *                  example=4,
     *              ),
     *              @SWG\Property(
     *                  property="client_secret",
     *                  type="string",
     *                  description="client secret key in api db",
     *                  example="F86EQbRRvSBOObZ0QWnEkGkG5tbckpI6j4Rg7LNA",
     *              ),
     *              @SWG\Property(
     *                  property="scope",
     *                  type="string",
     *                  description="access actions for user",
     *                  example="*",
     *              ),
     *              @SWG\Property(
     *                  property="username",
     *                  type="string",
     *                  description="",
     *                  example="test@example.com",
     *              ),
     *              @SWG\Property(
     *                  property="password",
     *                  type="string",
     *                  description="",
     *                  example="12341234",
     *              ),
     *          ),
     *     ),
     *     @SWG\Response(
     *      response=200,
     *      description="tokens",
     *      examples={
     *          "application/json":{
     *              "token_type":"Bearer",
     *              "expires_in":31535999,
     *              "access_token":"eyJ0eXAiOiJKV1Qj1MjOGYQ4OTY0ODY3LCJzdWIiOiIxMSIsInNjb3BlcyI6WyIqIl19",
     *              "refresh_token":"def50200e6a2f6c779665a8564"
     *           }
     *     }
     *     ),
     *
     *     @SWG\Response(
     *         response=400,
     *         description="Bad request",
     *         examples={
     *           "application/json":{
     *              "error": "invalid_request",
     *              "message": "The request is missing a required parameter, includes an invalid parameter value, includes a parameter more than once, or is otherwise malformed.",
     *              "hint": "Check the `username` parameter"
     *           },
     *         },
     *     ),
     *
     *     @SWG\Response(
     *         response=401,
     *         description="Invalid Credentials",
     *         examples={
     *           "application/json":{
     *              "error": "invalid_credentials",
     *              "message": "The user credentials were incorrect."
     *           },
     *         },
     *     ),
     * ),
     */
}
