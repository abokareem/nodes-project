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
 *      property="amount",
 *      type="integer",
 *      description="",
 *      example=111
 *     ),
 *     @SWG\Property(
 *      property="start",
 *      type="object",
 *      description="",
 *      @SWG\Property(
 *       property="date",
 *       type="string",
 *       description="Start time.",
 *       example="2018-02-24 11:10:34.000000"
 *      ),
 *     @SWG\Property(
 *       property="timezone_type",
 *       type="integer",
 *       description="Start time.",
 *       example=3
 *      ),
 *     @SWG\Property(
 *       property="timezone",
 *       type="string",
 *       description="Start time.",
 *       example="UTC"
 *      ),
 *     ),
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
 *      property="profit",
 *      description="",
 *      ref="#/definitions/UserProfit"
 *     ),
 * )
 *
 */
class UserInvestResource extends Resource
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
            'amount' => $this->amount,
            'start' => $this->created_at,
            'currency' => new CurrencyResource($this->currency),
            'profit' => new UserProfitResource($this->getProfit())
        ];
    }

    /**
     * @return mixed
     */
    private function getProfit()
    {
        $user = $this->user;
        $profit = $user->profits()->where('node_id', $this->node->id)->first();
        return $profit;
    }
}
