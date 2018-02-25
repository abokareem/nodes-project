<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Requests\Api\System\UpdateUserBillRequest;
use App\Http\Resources\UserBillsResource;
use App\UserBill;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserBillController extends Controller
{
    /**
     *
     * @SWG\Get(
     *     path="/bills",
     *     summary="Get bills",
     *     tags={"Admin"},
     *     description="List of bills",
     *     operationId="getBills",
     *     security={
     *         {
     *             "Bearer": {}
     *         }
     *     },
     *     @SWG\Response(
     *      response=200,
     *      description="bills objects",
     *      @SWG\Schema(
     *       title="Result",
     *       @SWG\Property(
     *        property="data",
     *        type="array",
     *        @SWG\Items(ref="#/definitions/UserBills")
     *       )
     *      )
     *     ),
     * )
     *
     * @return UserBillsResource
     */
    public function index()
    {
        return UserBillsResource::collection(UserBill::paginate());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     *
     * @SWG\Patch(
     *     path="/bills/{bill}",
     *     summary="Update bill",
     *     tags={"Admin"},
     *     description="Update bill",
     *     operationId="updateBill",
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
     *           @SWG\Property(
     *                  property="name",
     *                  type="string",
     *                  description="",
     *                  example="first",
     *              ),
     *              @SWG\Property(
     *                  property="code",
     *                  type="string",
     *                  description="",
     *                  example="USD",
     *              ),
     *              @SWG\Property(
     *                  property="symbol",
     *                  type="string",
     *                  description="",
     *                  example="@",
     *              ),
     *          ),
     *     ),
     *     @SWG\Response(
     *      response=201,
     *      description="Create currency",
     *      @SWG\Schema(
     *       title="Result",
     *       @SWG\Property(
     *        property="data",
     *        ref="#/definitions/Currency"
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
     * @param  UpdateUserBillRequest  $request
     * @param  UserBill  $bill
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserBillRequest $request, UserBill $bill)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
