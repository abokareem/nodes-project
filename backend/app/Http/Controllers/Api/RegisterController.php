<?php

namespace App\Http\Controllers\Api;

use App\Events\UserRegistered;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\RegisterResource;
use App\User;

class RegisterController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     *
     * @SWG\Post(
     *     path="/users",
     *     summary="Create User",
     *     tags={"Users"},
     *     description="Create user",
     *     operationId="createUser",
     *
     *     @SWG\Parameter(
     *          name="user",
     *          in="body",
     *          required=true,
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="name",
     *                  type="string",
     *                  description="",
     *                  example="test",
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
     *              @SWG\Property(
     *                  property="language",
     *                  type="string",
     *                  description="",
     *                  example="ru",
     *              ),
     *          ),
     *     ),
     *
     *     @SWG\Response(
     *      response=201,
     *      description="Created user",
     *      examples={
     *           "application/json":{
     *             "message": "A confirmation email has been sent. Please confirm your mail.",
     *           },
     *         },
     *     ),
     *
     *     @SWG\Response(
     *         response=422,
     *         description="user",
     *         examples={
     *           "application/json":{
     *             "message": "The given data was invalid",
     *             "errors":{
     *                 "password": {"The password field is required."},
     *                 "last_name": {"The last name field is required."}
     *             },
     *           },
     *         },
     *     ),
     * )
     *
     *
     * @param  RegisterRequest $request
     *
     * @return RegisterResource
     */
    public function store(RegisterRequest $request)
    {
        $user = User::create($request->all());

        event(new UserRegistered($user));

        return new RegisterResource(trans('mails.confirm'));

    }
}
