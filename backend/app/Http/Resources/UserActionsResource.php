<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;


/**
 * Class UserActionsResource
 *
 * @SWG\Definition(
 *     definition="UserActions",
 *     title="UserActions",
 *     @SWG\Property(
 *      property="message",
 *      type="string",
 *      description="",
 *      example="...some message..."
 *     ),
 * )
 *
 */
class UserActionsResource extends Resource
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
            'message' => trans($this->message,['email' => Auth::user()->email])
        ];
    }
}
