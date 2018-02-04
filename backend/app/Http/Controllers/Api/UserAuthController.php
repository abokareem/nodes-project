<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TwoFaLoginRequest;
use App\Http\Resources\Login;
use App\Http\Resources\TwoFaLoginFirstStep;
use App\Http\Resources\TwoFaLoginSecondStep;
use App\Services\TwoFaService;
use App\User;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ServerRequestInterface;

class UserAuthController extends Controller
{
    /**
     * @SWG\Post(
     *     path="/users/auth",
     *     summary="authenticate user",
     *     tags={"Users"},
     *     description="authenticate user",
     *     operationId="authenticateUser",
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
     *      response=100,
     *      description="tokens object",
     *      @SWG\Schema(
     *       title="Result",
     *       @SWG\Property(
     *        property="data",
     *        ref="#/definitions/TwoFaLoginFirst"
     *       )
     *      )
     *     ),
     *     @SWG\Response(
     *      response=200,
     *      description="tokens object",
     *      @SWG\Schema(
     *       title="Result",
     *       @SWG\Property(
     *        property="data",
     *        ref="#/definitions/Login"
     *       )
     *      )
     *     ),
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
     * )
     *
     *
     * @param ServerRequestInterface $request
     * @param AccessTokenController $accessToken
     * @param TwoFaService $service2FA
     * @return TwoFaLoginFirstStep|\Illuminate\Http\Response
     */
    public function login(
        ServerRequestInterface $request,
        AccessTokenController $accessToken,
        TwoFaService $service2FA
    ) {
        $tokens = $accessToken->issueToken($request);

        if ($tokens->status() === 200) {

            $user = User::where('email', $request->getParsedBody()['username'])->firstOrFail();

            if ($service2FA->isEnable($user)) {

                $user->twoFa()->delete();

                $userLoginRecord = $user->twoFa()->create([
                    'token' => encrypt($user->email),
                    'auth_user_data' => $tokens->getContent()
                ]);

                return new TwoFaLoginFirstStep($userLoginRecord);
            }
        }

        return new Login($tokens);
    }

    /**
     * @SWG\Post(
     *     path="/users/auth/twofa",
     *     summary="authenticate 2fa user",
     *     tags={"Users"},
     *     description="authenticate 2fa user",
     *     operationId="authenticateTFAUser",
     *     @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          required=true,
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="token",
     *                  type="string",
     *                  description="some token",
     *                  example="...token...",
     *              ),
     *              @SWG\Property(
     *                  property="code",
     *                  type="string",
     *                  description="authorize code",
     *                  example="345567",
     *              ),
     *          ),
     *     ),
     *     @SWG\Response(
     *      response=200,
     *      description="tokens object",
     *      @SWG\Schema(
     *       title="Result",
     *       @SWG\Property(
     *        property="data",
     *        ref="#/definitions/TwoFaLoginSecond"
     *       )
     *      )
     *     ),
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
     * )
     *
     *
     * @param TwoFaLoginRequest $request
     * @param TwoFaService $service2FA
     * @return TwoFaLoginSecondStep
     * @throws OAuthServerException
     */
    public function twoFaLogin(TwoFaLoginRequest $request, TwoFaService $service2FA)
    {
        $user = User::where('email', decrypt($request->get('token')))->firstOrFail();

        $code = str_replace(" ", "", $request->get('code'));

        $isValid = $service2FA->verifyCode($user->google2fa_secret, $code);

        if ($isValid) {

            $tokens = $user->twoFa()->latest()->firstOrFail();

            return new TwoFaLoginSecondStep($tokens);

        }

        throw OAuthServerException::invalidCredentials();
    }
}
