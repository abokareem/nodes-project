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
        $this::withoutWrapping();

        $content = (array)json_decode($this->getContent());

        return [
            'data' => $content
        ];
    }

    public function withResponse($request, $response)
    {
        $response->setStatusCode($this->status());
    }
}
