<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class SystemCommissionResource
 *
 * @SWG\Definition(
 *     definition="Commissions",
 *     title="Commissions",
 *     @SWG\Property(
 *      property="type",
 *      type="string",
 *      description="",
 *      example="replenishment"
 *     ),
 *     @SWG\Property(
 *      property="percent",
 *      type="string",
 *      description="Commission percent",
 *      example="12"
 *     ),
 * )
 *
 */
class SystemCommissionResource extends Resource
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
            'type' => $this->type,
            'percent' => $this->percent
        ];
    }
}
