<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class TwoFaEnableResource
 *
 * @SWG\Definition(
 *     definition="TwoFaEnable",
 *     title="TwoFaEnable",
 *     @SWG\Property(
 *      property="qr_code",
 *      type="string",
 *      description="",
 *      example="...some qr code..."
 *     ),
 *     @SWG\Property(
 *      property="hash",
 *      type="int",
 *      description="",
 *      example="some secret code"
 *     ),
 * )
 *
 */

class TwoFaEnableResource extends Resource
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
            'qr_code' => $this->getQrCode(),
            'hash' => $this->getSecretKey(),
            'reserve_code' => $this->getReserveCode()
        ];
    }
}
