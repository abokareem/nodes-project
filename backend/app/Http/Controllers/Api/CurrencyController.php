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
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return CurrencyResource::collection(Currency::paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateCurrencyRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(CreateCurrencyRequest $request)
    {
        $currency = $request->only('name', 'symbol');

        Currency::create($currency);

        return response('', Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
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
     * @param UpdateCurrencyRequest $request
     * @param Currency $currency
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(UpdateCurrencyRequest $request, Currency $currency)
    {
        $updatedCurrency = $request->only('name', 'symbol');

        $currency->update($updatedCurrency);

        return response('', 200);
    }
}
