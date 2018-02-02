<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class Login
 *
 * @SWG\Definition(
 *     definition="Login",
 *     title="Login",
 *     @SWG\Property(
 *      property="token_type",
 *      type="string",
 *      description="",
 *      example="Bearer"
 *     ),
 *     @SWG\Property(
 *      property="expires_in",
 *      type="int",
 *      description="Seconds at expire",
 *      example=31536000
 *     ),
 *     @SWG\Property(
 *      property="access_token",
 *      type="string",
 *      description="",
 *      example="...some token..."
 *     ),
 *     @SWG\Property(
 *      property="refresh_token",
 *      type="string",
 *      description="",
 *      example="...some token..."
 *     ),
 * )
 *
 */
class Login extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $content = json_decode($this->getContent());

        return [
            "token_type" => $content->token_type,
            "expires_in" => $content->expires_in,
            "access_token" => $content->access_token,
            "refresh_token" => $content->refresh_token
        ];
    }
}
