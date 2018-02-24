<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;


/**
 * Class UserTransactionsResource
 *
 * @SWG\Definition(
 *     definition="Transactions",
 *     title="Transactions",
 *     @SWG\Property(
 *      property="id",
 *      type="integer",
 *      description="",
 *      example=1
 *     ),
 *     @SWG\Property(
 *      property="message",
 *      type="integer",
 *      description="",
 *      example="some message"
 *     ),
 *     @SWG\Property(
 *      property="created",
 *      type="string",
 *      description="",
 *      example="1234123"
 *     ),
 *     @SWG\Property(
 *      property="amount",
 *      type="integer",
 *      description="",
 *      example=210
 *     ),
 *     @SWG\Property(
 *      property="currency",
 *      description="",
 *      ref="#/definitions/Currency"
 *     ),
 * )
 *
 *
 */
class UserTransactionsResource extends Resource
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
            'id' => $this->id,
            'amount' => $this->amount,
            'message' => trans($this->message),
            'created' => $this->created_at,
            'data' => $this->data,
            'currency' => new CurrencyResource($this->currency)
        ];
    }
}
