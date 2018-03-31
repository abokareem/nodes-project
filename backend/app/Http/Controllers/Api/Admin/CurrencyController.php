<?php

namespace App\Http\Controllers\Api\Admin;

use App\Currency;
use App\Http\Resources\Admin\CurrencyResource;
use App\Http\Controllers\Controller;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @SWG\Get(
     *     path="/admin/currency",
     *     summary="Get currencies",
     *     tags={"Admin"},
     *     description="List of currencies",
     *     operationId="getCurrencies",
     *     security={
     *         {
     *             "Bearer": {}
     *         }
     *     },
     *     @SWG\Response(
     *      response=200,
     *      description="currency objects",
     *      @SWG\Schema(
     *       title="Result",
     *       @SWG\Property(
     *        property="data",
     *        type="array",
     *        @SWG\Items(ref="#/definitions/AdminCurrency")
     *       )
     *      )
     *     ),
     * )
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return CurrencyResource::collection(Currency::all());
    }
}
