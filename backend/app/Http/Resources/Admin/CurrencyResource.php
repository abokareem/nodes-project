<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\MasternodeShareResource;
use Illuminate\Http\Resources\Json\Resource;

/**
 * Class CurrencyResource
 *
 * @SWG\Definition(
 *     definition="AdminCurrency",
 *     title="AdminCurrency",
 *     @SWG\Property(
 *      property="name",
 *      type="string",
 *      description="",
 *      example="dollars"
 *     ),
 *     @SWG\Property(
 *      property="code",
 *      type="string",
 *      description="",
 *      example="USD"
 *     ),
 *     @SWG\Property(
 *      property="symbol",
 *      type="string",
 *      description="symbol of currency",
 *      example="$"
 *     ),
 *     @SWG\Property(
 *      property="share",
 *      description="",
 *      ref="#/definitions/MasternodeShares"
 *     ),
 * )
 *
 */
class CurrencyResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'symbol' => $this->symbol,
            'freeWallets' => $this->freeWallets,
            'share' => new MasternodeShareResource($this->share)
        ];
    }
}
