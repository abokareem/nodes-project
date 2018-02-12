<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class ForgotPassword
 *
 * @SWG\Definition(
 *     definition="ForgotPassword",
 *     title="ForgotPassword",
 *     @SWG\Property(
 *      property="email",
 *      type="string",
 *      example="test@example.com"
 *     )
 * )
 *
 */


class ForgotPasswordResource extends Resource
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
            'message' => $this->resource
        ];
    }

}
