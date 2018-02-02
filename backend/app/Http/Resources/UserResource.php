<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class User
 *
 * @SWG\Definition(
 *     definition="User",
 *     title="User",
 *     @SWG\Property(
 *      property="id",
 *      type="integer",
 *      description="",
 *      example=1
 *     ),
 *     @SWG\Property(
 *      property="email",
 *      type="string",
 *      description="",
 *      example="test@example.com"
 *     ),
 *     @SWG\Property(
 *      property="email_confirmed",
 *      type="boolean",
 *      description="",
 *      example=true
 *     ),
 *     @SWG\Property(
 *      property="two_fa",
 *      type="boolean",
 *      description="",
 *      example=false
 *     ),
 * )
 *
 * @mixin \App\User
 */
class UserResource extends Resource
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
            'email' => $this->email,
            'email_confirmed' => $this->email_confirmed,
            'two_fa' => $this->two_fa
        ];
    }
}
