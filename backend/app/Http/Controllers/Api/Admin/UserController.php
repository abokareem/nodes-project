<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Requests\Api\Admin\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @SWG\Get(
     *     path="/admin/users",
     *     summary="Get Users",
     *     tags={"Admin"},
     *     description="This request cannot be done without authorization.",
     *     operationId="getUser",
     *     security={
     *         {
     *             "Bearer": {}
     *         }
     *     },
     *     @SWG\Response(
     *      response=200,
     *      description="users object",
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
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return UserResource::collection(User::withTrashed()->get());
    }

    /**
     * Display the specified resource.
     *
     * @SWG\Get(
     *     path="/admin/users/{user}",
     *     summary="Get User",
     *     tags={"Users"},
     *     description="This request cannot be done without authorization.",
     *     operationId="getUser",
     *     security={
     *         {
     *             "Bearer": {}
     *         }
     *     },
     *     @SWG\Parameter(
     *          name="user",
     *          in="path",
     *          type="integer",
     *          required=true
     *      ),
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
     * @param User $user
     * @return \App\Http\Resources\UserResource
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @SWG\Patch(
     *     path="/admin/users/{user}",
     *     summary="Update User",
     *     tags={"Admin"},
     *     description="Update user",
     *     operationId="updateUser",
     *     security={
     *         {
     *             "Bearer": {}
     *         }
     *     },
     *     @SWG\Parameter(
     *          name="user",
     *          in="path",
     *          type="integer",
     *          required=true
     *      ),
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
     *                  property="group_id",
     *                  type="number",
     *                  description="",
     *                  example="1",
     *              ),
     *              @SWG\Property(
     *                  property="language",
     *                  type="string",
     *                  description="",
     *                  example="ru",
     *              ),
     *              @SWG\Property(
     *                  property="subscribe",
     *                  type="boolean",
     *                  description="",
     *                  example="true",
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
     * @param  \App\Http\Requests\Api\Admin\UpdateUserRequest $request
     * @param User $user
     * @return \App\Http\Resources\UserResource
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->only(['group_id', 'name', 'language', 'subscribe']);

        $user->update($data);

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @SWG\Delete(
     *     path="/admin/users/{user}",
     *     summary="Soft delete User",
     *     tags={"Admin"},
     *     description="This request cannot be done without authorization.",
     *     operationId="deleteUser",
     *     security={
     *         {
     *             "Bearer": {}
     *         }
     *     },
     *     @SWG\Parameter(
     *          name="user",
     *          in="path",
     *          type="integer",
     *          required=true
     *      ),
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
     * @param User $user
     * @return \App\Http\Resources\UserResource
     */
    public function destroy(User $user)
    {
        $user->delete();

        return new UserResource($user);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @SWG\Put(
     *     path="/admin/users/{user}",
     *     summary="Restore deleted User",
     *     tags={"Admin"},
     *     description="This request cannot be done without authorization.",
     *     operationId="restoreUser",
     *     security={
     *         {
     *             "Bearer": {}
     *         }
     *     },
     *     @SWG\Parameter(
     *          name="user",
     *          in="path",
     *          type="integer",
     *          required=true
     *      ),
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
     * @param int $id
     * @return \App\Http\Resources\UserResource
     */
    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        $user->restore();

        return new UserResource($user);
    }
}
