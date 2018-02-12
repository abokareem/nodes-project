<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class AdminAccessException extends Exception
{
    public function render($request)
    {
        if ($request->expectsJson()) {

            return response(['message' => trans('exceptions.admin.access')],
                Response::HTTP_FORBIDDEN);

        }
    }
}
