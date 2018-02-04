<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class TwoFaSecretNotExists extends Exception
{
    public function render($request)
    {
        if ($request->expectsJson()) {

            return response(['message' => trans('exceptions.two_fa.server')],
                Response::HTTP_INTERNAL_SERVER_ERROR);

        }
    }
}
