<?php

namespace App\Http\Controllers\Api;

use App\Events\ForgottenPasswordRequested;
use App\Http\Requests\Api\ForgotPasswordRequest;
use App\Http\Resources\ForgotPasswordResource;
use App\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Http\Controllers\Controller;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    /**
     *
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Api\ForgotPasswordRequest $request
     * @return \App\Http\Resources\ForgotPasswordResource
     *
     * @SWG\Post(
     *     path="/users/password",
     *     summary="Generate token",
     *     tags={"Users"},
     *     description="Generate token and send it on email",
     *     operationId="generateToken",
     *     @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          required=true,
     *          @SWG\Schema(
     *             ref="#/definitions/ForgotPassword"
     *          ),
     *      ),
     *     @SWG\Response(
     *         response=200,
     *         description="Password reset link sent",
     *         examples={
     *              "message":"password reset link sent"
     *         },
     *     ),
     * )
     */
    public function store(ForgotPasswordRequest $request)
    {
        $user = User::where('email', $request->input('email'))->firstOrFail();

        $token = $this->broker()->createToken($user);

        event(new ForgottenPasswordRequested($user, $token));

        return new ForgotPasswordResource(trans('passwords.sent'));
    }
}
