<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class TwoFaInvalidCode extends Exception
{
    public function render($request)
    {
        if ($request->expectsJson()) {

            return response(['message' => trans('exceptions.two_fa.code')],
                Response::HTTP_FORBIDDEN);

        }
    }
}
