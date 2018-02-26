<?php

namespace App\Http\Resources;

use App\Services\Math\MathInterface;
use App\Withdrawals;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;

/**
 * Class MasternodeResource
 *
 * @SWG\Definition(
 *     definition="Masternode",
 *     type="object",
 *     title="Masternode",
 *     @SWG\Property(
 *      property="id",
 *      type="integer",
 *      description="",
 *      example=1
 *     ),
 *     @SWG\Property(
 *      property="state",
 *      type="string",
 *      description="",
 *      example="new"
 *     ),
 *     @SWG\Property(
 *      property="type",
 *      type="string",
 *      description="Masternode type",
 *      example="single"
 *     ),
 *     @SWG\Property(
 *      property="price",
 *      type="integer",
 *      description="",
 *      example=150
 *     ),
 * )
 *
 */
class MasternodeResource extends Resource
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
            'state' => $this->state,
            'type' => $this->type,
            'price' => $this->price,
            'free_shares' => $this->getFreeShares(),
            'bill' => new MasternodeBillResource($this->bill),
            'currency' => new CurrencyResource($this->currency),
            'withdrawals' => WithdrawalResource::collection($this->getWithdrawals()),
            'investor' => $this->getInvestor()
        ];
    }

    /**
     * @return UserInvestResource
     */
    private function getInvestor()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $investor = $user->investments()->where('node_id', $this->id)->first();

            if ($investor) {
                return new UserInvestResource($investor);
            }
        }
    }

    /**
     * @return mixed
     */
    private function getWithdrawals()
    {
        return $this->withdrawals()->where('state', Withdrawals::PROCESSING_STATE)->get();
    }

    /**
     * @return mixed
     */
    private function getFreeShares()
    {
        $math = app(MathInterface::class);
        $withdrawals = $this->withdrawals()->where('state', Withdrawals::PROCESSING_STATE)->get();
        $freeAmount = 0;
        $freeNodeAmount = $math->sub($this->price, $this->bill->amount);

        foreach ($withdrawals as $withdrawal) {
            $freeAmount = $math->add($freeAmount, $withdrawal->amount);
        }

        $freeAmount = $math->add($freeAmount, $freeNodeAmount);
        $freeShares = $math->divide($freeAmount, $this->currency->share->share_price);

        return $freeShares;
    }
}
