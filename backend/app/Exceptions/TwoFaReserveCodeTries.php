<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class TwoFaReserveCodeTries extends Exception
{
    public function render($request)
    {
        if ($request->expectsJson()) {

            return response(['message' => trans('exceptions.two_fa.reserve')],
                Response::HTTP_FORBIDDEN);

        }
    }
}
