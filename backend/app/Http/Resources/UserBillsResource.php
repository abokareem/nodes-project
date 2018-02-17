<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class UserInvestResource
 *
 * @SWG\Definition(
 *     definition="UserBills",
 *     title="UserBills",
 *     @SWG\Property(
 *      property="currency",
 *      description="",
 *      ref="#/definitions/Currency"
 *     ),
 *     @SWG\Property(
 *      property="bill",
 *      type="string",
 *      description="",
 *      example="some hash"
 *     ),
 *     @SWG\Property(
 *      property="amount",
 *      type="integer",
 *      description="",
 *      example=111
 *     ),
 * )
 *
 */

class UserBillsResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'currency' => new CurrencyResource($this->currency),
            'bill' => $this->uuid,
            'amount' => $this->amount
        ];
    }
}
