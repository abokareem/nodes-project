<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class CurrencyResource
 *
 * @SWG\Definition(
 *     definition="Currency",
 *     title="Currency",
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
 *     )
 * )
 *
 */
class CurrencyResource extends Resource
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
            'name' => $this->name,
            'code' => $this->code,
            'symbol' => $this->symbol
        ];
    }
}
