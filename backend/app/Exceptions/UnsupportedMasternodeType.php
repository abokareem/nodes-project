<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class UnsupportedMasternodeType extends Exception
{
    public function render($request)
    {
        if ($request->expectsJson()) {

            return response(['message' => trans('exceptions.node.type')],
                Response::HTTP_BAD_REQUEST);

        }
    }
}
