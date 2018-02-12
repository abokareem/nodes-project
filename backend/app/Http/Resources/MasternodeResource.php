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
 *      property="name",
 *      type="string",
 *      description="",
 *      example="some name"
 *     ),
 *     @SWG\Property(
 *      property="description",
 *      type="string",
 *      description="Masternode description",
 *      example="some description"
 *     ),
 *     @SWG\Property(
 *      property="income",
 *      type="integer",
 *      description="",
 *      example="0.1"
 *     ),
 *     @SWG\Property(
 *      property="price",
 *      type="integer",
 *      description="",
 *      example=150
 *     ),
 *     @SWG\Property(
 *      property="share",
 *      description="",
 *      ref="#/definitions/MasternodeShares"
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
            'name' => $this->name,
            'description' => $this->description,
            'income' => $this->income,
            'price' => $this->price,
            'share' => new MasternodeShareResource($this->share)
        ];
    }
}
