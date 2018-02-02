<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

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
            'secret_code' => $this->getSecretKey()
        ];
    }
}
