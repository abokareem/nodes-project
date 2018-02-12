<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Http\Response;

class RegisterResource extends Resource
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

    public function withResponse($request, $response)
    {
        $response->setStatusCode(Response::HTTP_CREATED);
    }
}
