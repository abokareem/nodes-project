<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class MasternodeShareResource
 *
 * @SWG\Definition(
 *     definition="MasternodeShares",
 *     title="MasternodeShares",
 *     @SWG\Property(
 *       property="id",
 *       type="integer",
 *       description="",
 *       example=1
 *      ),
 *     @SWG\Property(
 *       property="price",
 *       type="integer",
 *       description="",
 *       example=10
 *      ),
 *      @SWG\Property(
 *       property="count",
 *       type="integer",
 *       description="",
 *       example=15
 *      ),
 * )
 *
 */

class MasternodeShareResource extends Resource
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
            'price' => $this->price,
            'count' => $this->count
        ];
    }
}
