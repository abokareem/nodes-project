<?php

namespace App\Http\Controllers\Api;

use App\Currency;
use App\Http\Requests\Api\CreateCurrencyRequest;
use App\Http\Requests\Api\UpdateCurrencyRequest;
use App\Http\Resources\CurrencyResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @SWG\Get(
     *     path="/currency",
     *     summary="Get currencies",
     *     tags={"Currency"},
     *     description="List of currencies",
     *     operationId="getCurrencies",
     *     @SWG\Response(
     *      response=200,
     *      description="currency objects",
     *      @SWG\Schema(
     *       title="Result",
     *       @SWG\Property(
     *        property="data",
     *        ref="#/definitions/Currency"
     *       )
     *      )
     *     ),
     * )
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return CurrencyResource::collection(Currency::paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @SWG\Post(
     *     path="/currency",
     *     summary="Create currency",
     *     tags={"Currency"},
     *     description="Create currency",
     *     operationId="createCurrency",
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
     * @param CreateCurrencyRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(CreateCurrencyRequest $request)
    {
        $currency = $request->only('name', 'code', 'symbol');

        Currency::create($currency);

        return response('', Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @SWG\Get(
     *     path="/currency/{currency}",
     *     summary="Get currency",
     *     tags={"Currency"},
     *     description="One currency",
     *     operationId="getCurrency",
     *     @SWG\Parameter(
     *          name="currency",
     *          in="path",
     *          type="integer",
     *          required=true
     *      ),
     *     @SWG\Response(
     *      response=200,
     *      description="currency object",
     *      @SWG\Schema(
     *       title="Result",
     *       @SWG\Property(
     *        property="data",
     *        ref="#/definitions/Currency"
     *       )
     *      )
     *     ),
     * )
     *
     * @param Currency $currency
     * @return CurrencyResource
     */
    public function show(Currency $currency)
    {
        return new CurrencyResource($currency);
    }

    /**
     * Update the specified resource in storage.
     *
     * @SWG\Patch(
     *     path="/currency/{currency}",
     *     summary="Update currency",
     *     tags={"Currency"},
     *     description="Update currency",
     *     operationId="updateCurrency",
     *     security={
     *         {
     *             "Bearer": {}
     *         }
     *     },
     *     @SWG\Parameter(
     *          name="currency",
     *          in="path",
     *          type="integer",
     *          required=true
     *      ),
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
     *      response=200,
     *      description="Update currency",
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
     * @param UpdateCurrencyRequest $request
     * @param Currency $currency
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(UpdateCurrencyRequest $request, Currency $currency)
    {
        $updatedCurrency = $request->only('name', 'code', 'symbol');

        $currency->update($updatedCurrency);

        return response('', 200);
    }
}
