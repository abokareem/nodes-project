<?php

namespace App\Http\Controllers\Api;

use App\EmailConfirmation;
use App\Events\ResendUserRegisteredEmail;
use App\Exceptions\MaxSentUserRegisteredEmails;
use App\Exceptions\TwoFaInvalidCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\DisableTwoFaRequest;
use App\Http\Requests\Api\EnableTwoFaRequest;
use App\Http\Requests\Api\TwoFaResetRequest;
use App\Http\Resources\TwoFaEnableResource;
use App\Http\Resources\UserResource;
use App\Services\TwoFaService;
use Illuminate\Http\Response;
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

        return response(trans('mails.confirmed'));
    }

    /**
     *
     * @SWG\Get(
     *     path="/users/email/resend",
     *     summary="Resend confirm email",
     *     tags={"Users"},
     *     description="Resend confirm user email.",
     *     operationId="resendEmail",
     *     security={
     *         {
     *             "Bearer": {}
     *         }
     *     },
     *     @SWG\Response(
     *      response=200,
     *      description="Email resent"
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
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws MaxSentUserRegisteredEmails
     */
    public function resendConfirmEmail()
    {
        $user = Auth::user();

        $confirmation = EmailConfirmation::where('user_id', $user->id)
            ->latest()->firstOrFail();

        if ($confirmation->email_confirmed_tries === 5) {
            throw new MaxSentUserRegisteredEmails();
        }

        event(new ResendUserRegisteredEmail($confirmation));

        return response(trans('emails.reconfirm'));
    }

    /**
     * @SWG\Get(
     *     path="/users/twofa",
     *     summary="Get 2FA data for user to activate 2fa",
     *     tags={"Users"},
     *     description="This request cannot be done without authorization.",
     *     operationId="getTwoFa",
     *     security={
     *         {
     *             "Bearer": {}
     *         }
     *     },
     *     @SWG\Response(
     *      response=200,
     *      description="2fa object",
     *      @SWG\Schema(
     *       title="Result",
     *       @SWG\Property(
     *        property="data",
     *        ref="#/definitions/TwoFaEnable"
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
     * @param TwoFaService $service2FA
     * @return TwoFaEnableResource
     */
    public function twoFaDataForActivate(TwoFaService $service2FA)
    {
        $type2FA = $service2FA->generateDataForActivate();

        return new TwoFaEnableResource($type2FA);
    }

    /**
     *
     * @SWG\Post(
     *     path="/users/twofa",
     *     summary="enable 2fa for user",
     *     tags={"Users"},
     *     description="enable 2fa for user",
     *     operationId="enableTFA",
     *     security={
     *         {
     *             "Bearer": {}
     *         }
     *     },
     *     @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          required=true,
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="hash",
     *                  type="string",
     *                  description="some hash",
     *                  example="...some hash...",
     *              ),
     *              @SWG\Property(
     *                  property="code",
     *                  type="string",
     *                  description="authorize code",
     *                  example="345567",
     *              ),
     *              @SWG\Property(
     *                  property="reserve_code",
     *                  type="string",
     *                  description="reserve code",
     *                  example="asdd1234",
     *              ),
     *          ),
     *     ),
     *     @SWG\Response(
     *      response=200,
     *      description="success result"
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
     *     @SWG\Response(
     *         response=403,
     *         description="Invalid code",
     *         examples={
     *           "application/json":{
     *               "message":"invalid code"
     *           },
     *         },
     *     ),
     * )
     *
     * @param EnableTwoFaRequest $request
     * @param TwoFaService $service2FA
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws TwoFaInvalidCode
     */
    public function enableTwoFa(EnableTwoFaRequest $request, TwoFaService $service2FA)
    {
        $hash = $request->get('hash');
        $code = str_replace(" ", "", $request->get('code'));
        $reserveCode = $request->get('reserve_code');

        $result = $service2FA->enable($hash, $code, $reserveCode);

        if ($result) {
            return response(trans('twofa.enabled'));
        }

        throw new TwoFaInvalidCode();
    }

    /**
     *
     * @SWG\Delete(
     *     path="/users/twofa",
     *     summary="disable 2fa for user",
     *     tags={"Users"},
     *     description="disable 2fa for user",
     *     operationId="disableTFA",
     *     security={
     *         {
     *             "Bearer": {}
     *         }
     *     },
     *     @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          required=true,
     *          @SWG\Schema(
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
     *      description="success result"
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
     *     @SWG\Response(
     *         response=403,
     *         description="Invalid code",
     *         examples={
     *           "application/json":{
     *               "message":"invalid code"
     *           },
     *         },
     *     ),
     * )
     *
     * @param DisableTwoFaRequest $request
     * @param TwoFaService $service2FA
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws TwoFaInvalidCode
     */
    public function disableTwoFa(DisableTwoFaRequest $request, TwoFaService $service2FA)
    {
        $code = str_replace(" ", "", $request->get('code'));

        $result = $service2FA->disable($code);

        if ($result) {

            return response(trans('twofa.disabled'));
        }

        throw new TwoFaInvalidCode();
    }

    /**
     *
     * @SWG\Patch(
     *     path="/users/twofa",
     *     summary="reset 2fa for user",
     *     tags={"Users"},
     *     description="reset 2fa for user",
     *     operationId="resetTFA",
     *     @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          required=true,
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="email",
     *                  type="string",
     *                  description="user email",
     *                  example="test@example.com",
     *              ),
     *              @SWG\Property(
     *                  property="reserve_code",
     *                  type="string",
     *                  description="reserve code",
     *                  example="asdd1234",
     *              ),
     *          ),
     *     ),
     *     @SWG\Response(
     *      response=200,
     *      description="success result"
     *     ),
     *     @SWG\Response(
     *         response=403,
     *         description="Max tries for code",
     *         examples={
     *           "application/json":{
     *               "message":"Your account is blocked. Please contact support."
     *           },
     *         },
     *     ),
     * )
     *
     *
     * @param TwoFaResetRequest $request
     * @param TwoFaService $service2FA
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function resetTwoFa(TwoFaResetRequest $request, TwoFaService $service2FA)
    {
        $reserveCode = $request->get('reserve_code');
        $email = $request->get('email');

        $result = $service2FA->resetTwoFa($email, $reserveCode);

        if ($result) {
            return response(trans('twofa.disabled'));
        }

        return response(trans('twofa.reset.invalid'), Response::HTTP_FORBIDDEN);
    }
}
