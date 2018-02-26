<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class NoFreeSharesException extends Exception
{
    public function render($request)
    {
        if ($request->expectsJson()) {

            return response(['message' => trans('exceptions.node.free')],
                Response::HTTP_BAD_REQUEST);

        }
    }
}
