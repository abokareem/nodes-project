<?php

namespace App\Http\Controllers\Api;

use App\EmailConfirmation;
use App\Events\ConfirmedEmail;
use App\Events\ResendUserRegisteredEmail;
use App\Events\TwoFaDisable;
use App\Events\TwoFaEnable;
use App\Events\TwoFaReset;
use App\Events\UpdatedUserProfile;
use App\Exceptions\MaxSentUserRegisteredEmails;
use App\Exceptions\TwoFaInvalidCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\DisableTwoFaRequest;
use App\Http\Requests\Api\EnableTwoFaRequest;
use App\Http\Requests\Api\TwoFaResetRequest;
use App\Http\Requests\Api\UpdateUserRequest;
use App\Http\Resources\MasternodeResource;
use App\Http\Resources\MessageResource;
use App\Http\Resources\TwoFaEnableResource;
use App\Http\Resources\UserActionsResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserTransactionsResource;
use App\Http\Resources\WithdrawalResource;
use App\Services\TwoFaService;
use App\User;
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
     * Update the specified resource in storage.
     *
     *
     * @SWG\Patch(
     *     path="/users",
     *     summary="Update User",
     *     tags={"Users"},
     *     description="Update user",
     *     operationId="updateUser",
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
     *                  property="name",
     *                  type="string",
     *                  description="",
     *                  example="first",
     *              ),
     *              @SWG\Property(
     *                  property="email",
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
     *      description="Updated user",
     *      @SWG\Schema(
     *       title="Result",
     *       @SWG\Property(
     *        property="data",
     *        ref="#/definitions/User"
     *       )
     *      )
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
     * @param UpdateUserRequest $request
     * @return UserResource
     *
     */
    public function update(UpdateUserRequest $request)
    {
        $user = $request->only(['name', 'email', 'subscribe', 'password', 'language']);
        Auth::user()->update($user);
        event(new UpdatedUserProfile());

        return new UserResource(Auth::user());

    }

    /**
     * Display a listing of the resource.
     *
     *
     * @SWG\Get(
     *     path="/users/actions",
     *     summary="Get User actions",
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
     *      description="actions object",
     *      @SWG\Schema(
     *       title="Result",
     *       @SWG\Property(
     *        property="data",
     *        ref="#/definitions/UserActions"
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
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getActions()
    {
        $user = Auth::user();

        return UserActionsResource::collection($user->actions()->latest()->limit(5)->get());
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
     * @return MessageResource
     * @throws \Exception
     */
    public function confirmEmail(EmailConfirmation $token)
    {
        $user = $token->user;
        $user->email_confirmed = $token::CONFIRMED;
        $user->save();

        $token->delete();

        event(new ConfirmedEmail($user));

        return new MessageResource(trans('mails.confirmed'));
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
     * @return MessageResource
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

        return new MessageResource(trans('mails.reconfirm'));
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
     * @return MessageResource
     * @throws TwoFaInvalidCode
     */
    public function enableTwoFa(EnableTwoFaRequest $request, TwoFaService $service2FA)
    {
        $hash = $request->get('hash');
        $code = str_replace(" ", "", $request->get('code'));
        $reserveCode = $request->get('reserve_code');

        $result = $service2FA->enable($hash, $code, $reserveCode);

        if ($result) {
            event(new TwoFaEnable());
            return new MessageResource(trans('twofa.enabled'));
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
     * @return MessageResource
     * @throws TwoFaInvalidCode
     */
    public function disableTwoFa(DisableTwoFaRequest $request, TwoFaService $service2FA)
    {
        $code = str_replace(" ", "", $request->get('code'));

        $result = $service2FA->disable($code);

        if ($result) {
            event(new TwoFaDisable());
            return new MessageResource(trans('twofa.disabled'));
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
     * @return MessageResource|\Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \App\Exceptions\TwoFaReserveCodeTries
     */
    public function resetTwoFa(TwoFaResetRequest $request, TwoFaService $service2FA)
    {
        $reserveCode = $request->get('reserve_code');
        $email = $request->get('email');

        $result = $service2FA->resetTwoFa($email, $reserveCode);

        if ($result) {

            $user = User::where('email', $email)->firstOrFail();

            event(new TwoFaReset($user));
            return new MessageResource(trans('twofa.disabled'));
        }

        return response(trans('twofa.reset.invalid'), Response::HTTP_FORBIDDEN);
    }

    /**
     *
     * @SWG\Get(
     *     path="/users/nodes",
     *     summary="Get user nodes'",
     *     tags={"Users"},
     *     description="Get user nodes'.",
     *     operationId="getNodes",
     *     security={
     *         {
     *             "Bearer": {}
     *         }
     *     },
     *     @SWG\Response(
     *      response=200,
     *      description="Investments objects",
     *      @SWG\Schema(
     *       title="Result",
     *       @SWG\Property(
     *        property="data",
     *        type="array",
     *        @SWG\Items(ref="#/definitions/UserInvestments")
     *       )
     *      )
     *     ),
     * )
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getNodes()
    {
        return MasternodeResource::collection(Auth::user()->nodes);
    }

    /**
     *
     * @SWG\Get(
     *     path="/users/transactions",
     *     summary="Get user transactions'",
     *     tags={"Users"},
     *     description="Get user transactions.",
     *     operationId="getTransactions",
     *     security={
     *         {
     *             "Bearer": {}
     *         }
     *     },
     *     @SWG\Response(
     *      response=200,
     *      description="Transactions objects",
     *      @SWG\Schema(
     *       title="Result",
     *       @SWG\Property(
     *        property="data",
     *        type="array",
     *        @SWG\Items(ref="#/definitions/Transactions")
     *       )
     *      )
     *     ),
     * )
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getTransactions()
    {
        return UserTransactionsResource::collection(
            Auth::user()->transactions()->orderBy('created_at', 'desc')->limit(5)->get()
        );
    }

    /**
     *
     * @SWG\Get(
     *     path="/users/withdrawals",
     *     summary="Get user withdrawals'",
     *     tags={"Users"},
     *     description="Get user withdrawals.",
     *     operationId="getWithdrawals",
     *     security={
     *         {
     *             "Bearer": {}
     *         }
     *     },
     *     @SWG\Response(
     *      response=200,
     *      description="Withdrawals objects",
     *      @SWG\Schema(
     *       title="Result",
     *       @SWG\Property(
     *        property="data",
     *        type="array",
     *        @SWG\Items(ref="#/definitions/NodeWithdrawal")
     *       )
     *      )
     *     ),
     * )
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getWithdrawals()
    {
        return WithdrawalResource::collection(Auth::user()->withdrawals()->paginate());
    }
}
