<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Http\Response;

/**
 * Class TwoFaLoginFirstStep
 *
 * @SWG\Definition(
 *     definition="TwoFaLoginFirst",
 *     title="TwoFaLoginFirst",
 *     @SWG\Property(
 *      property="token",
 *      type="string",
 *      description="",
 *      example="...some token..."
 *     ),
 *     @SWG\Property(
 *      property="two_fa",
 *      type="boolean",
 *      description="",
 *      example=true
 *     )
 * )
 *
 */
class TwoFaLoginFirstStep extends Resource
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
            'token' => $this->token,
            'two_fa' => true
        ];
    }

    public function withResponse($request, $response)
    {
        $response->setStatusCode(Response::HTTP_CONTINUE);
    }
}
