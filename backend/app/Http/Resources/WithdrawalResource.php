<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class WithdrawalResource
 *
 * @SWG\Definition(
 *     definition="NodeWithdrawal",
 *     title="NodeWithdrawal",
 *     @SWG\Property(
 *      property="id",
 *      type="integer",
 *      description="",
 *      example=1
 *     ),
 *     @SWG\Property(
 *      property="node_id",
 *      type="integer",
 *      description="",
 *      example=2
 *     ),
 *     @SWG\Property(
 *      property="state",
 *      type="string",
 *      description="",
 *      example="approve"
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
class WithdrawalResource extends Resource
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
            'node_id' => $this->node_id,
            'state' => $this->state,
            'amount' => $this->amount,
            'currency' => new CurrencyResource($this->getCurrency()),
            'created' => $this->created_at
        ];
    }

    private function getCurrency()
    {
        if ($this->node) {
            return $this->node->currency;
        }
        return null;
    }
}
