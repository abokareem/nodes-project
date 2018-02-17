<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

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
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'state' => $this->state,
            'type' => $this->type,
            'price' => $this->price
        ];
    }
}
