<?php

namespace App\Http\Controllers\Api;

use App\EmailConfirmation;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
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
}
