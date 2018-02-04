<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class ResetPassword
 *
 * @SWG\Definition(
 *     definition="ResetPassword",
 *     title="ResetPassword",
 *     @SWG\Property(
 *      property="email",
 *      type="string",
 *      example="test@example.com"
 *     ),
 *     @SWG\Property(
 *      property="password",
 *      type="object",
 *      example="123456",
 *     ),
 *     @SWG\Property(
 *      property="token",
 *      type="object",
 *      example="asdfkqpowkec12k3c129cj1029cju0192j3cnokdnfsdcdon",
 *     ),
 * )
 *
 */
class ResetPasswordResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
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
