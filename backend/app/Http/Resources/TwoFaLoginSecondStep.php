<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class TwoFaLoginSecondStep
 *
 * @SWG\Definition(
 *     definition="TwoFaLoginSecond",
 *     title="TwoFaLoginSecond",
 *     @SWG\Property(
 *      property="data",
 *      description="",
 *      ref="#/definitions/Login"
 *     )
 * )
 *
 */

class TwoFaLoginSecondStep extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $this::withoutWrapping();

        return [
            'data' => $this->auth_user_data
        ];
    }
}
