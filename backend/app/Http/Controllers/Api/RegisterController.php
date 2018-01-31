<?php

namespace App\Http\Controllers\Api;

use App\Events\UserRegistered;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\User;

class RegisterController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     *
     * @SWG\Post(
     *     path="/api/users",
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
     *
     *     @SWG\Response(
     *      response=201,
     *      description="Created user",
     *      @SWG\Schema(
     *       title="Result",
     *       @SWG\Property(
     *        property="data",
     *        ref="#/definitions/User"
     *       )
     *      )
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
     * @return UserResource
     *
     */
    public function store(RegisterRequest $request)
    {
        $user = User::create($request->all());

        event(new UserRegistered($user));

        return new UserResource($user);

    }
}
