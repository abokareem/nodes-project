<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\UserBillsResource;
use Illuminate\Http\Resources\Json\Resource;

/**
 * Class WithdrawalMoneyResource
 *
 * @SWG\Definition(
 *     definition="AdminWithdrawalMoney",
 *     title="WithdrawalMoney",
 *     @SWG\Property(
 *      property="id",
 *      type="integer",
 *      description="",
 *      example="1"
 *     ),
 *     @SWG\Property(
 *      property="user",
 *      description="",
 *      ref="#/definitions/User"
 *     ),
 *     @SWG\Property(
 *      property="bill",
 *      description="",
 *      ref="#/definitions/UserBills"
 *     ),
 *     @SWG\Property(
 *      property="user_wallet",
 *      type="string",
 *      description="",
 *      example="some hash"
 *     ),
 *     @SWG\Property(
 *      property="state",
 *      type="string",
 *      description="",
 *      example="decline"
 *     ),
 *     @SWG\Property(
 *      property="amount",
 *      type="integer",
 *      description="",
 *      example="20"
 *     ),
 * )
 *
 */

class WithdrawalMoneyResource extends Resource
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
            'user' => $this->bill->user,
            'bill' => new UserBillsResource($this->bill),
            'user_wallet' => $this->external_user_wallet,
            'state' => $this->state,
            'amount' => $this->amount,
            'created' => $this->created_at
        ];
    }
}
