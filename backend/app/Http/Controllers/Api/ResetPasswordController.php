<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\ResetPasswordRequest;
use App\Http\Resources\ResetPasswordResource;
use Illuminate\Foundation\Auth\ResetsPasswords;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    /**
     *
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Api\ResetPasswordRequest $request
     * @return \App\Http\Resources\ResetPasswordResource
     *
     * @SWG\Patch(
     *     path="/users/password",
     *     summary="Reset password",
     *     tags={"Users"},
     *     description="Create new password",
     *     operationId="resetPassword",
     *     @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          required=true,
     *          @SWG\Schema(
     *             ref="#/definitions/ResetPassword"
     *          ),
     *      ),
     *     @SWG\Response(
     *         response=200,
     *         description="Password reset successfully.",
     *         examples={
     *              "message":"password was reset"
     *          }
     *     ),
     * )
     */
    public function update(ResetPasswordRequest $request)
    {
        $result = $this->broker()->reset(
            $this->credentials($request),

            function ($user, $password) {
                $user->password = $password;
                $user->save();
            }
        );

        switch ($result) {
            case Password::PASSWORD_RESET:

                return new ResetPasswordResource(trans('passwords.reset'));
            case Password::INVALID_USER:

                throw new HttpException(422, trans('passwords.user'));
            case Password::INVALID_PASSWORD:

                throw new HttpException(422, trans('passwords.password'));
            case Password::INVALID_TOKEN:

                throw new HttpException(422, trans('passwords.token'));
            default:

                throw new HttpException(422, trans('passwords.server'));
        }
    }


}
