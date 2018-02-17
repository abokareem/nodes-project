<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class UserInvestResource
 *
 * @SWG\Definition(
 *     definition="UserInvestments",
 *     title="UserInvestments",
 *     @SWG\Property(
 *      property="node",
 *      description="",
 *      ref="#/definitions/Masternode"
 *     ),
 *     @SWG\Property(
 *      property="currency",
 *      description="",
 *      ref="#/definitions/Currency"
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

class UserInvestResource extends Resource
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
            'node' => new MasternodeResource($this->node),
            'currency' => new CurrencyResource($this->currency),
            'amount' => $this->amount
        ];
    }
}
