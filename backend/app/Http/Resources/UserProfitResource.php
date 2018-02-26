<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class CurrencyResource
 *
 * @SWG\Definition(
 *     definition="UserProfit",
 *     title="Currency",
 *     @SWG\Property(
 *      property="per_day",
 *      type="string",
 *      description="",
 *      example="0.43"
 *     ),
 *     @SWG\Property(
 *      property="full",
 *      type="string",
 *      description="",
 *      example="1.23"
 *     )
 * )
 *
 */
class UserProfitResource extends Resource
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
            'per_day' => $this->per_day,
            'full' => $this->full
        ];
    }
}
