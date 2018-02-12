<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class InsolventException extends Exception
{
    public function render($request)
    {
        if ($request->expectsJson()) {

            return response(['message' => trans('exceptions.user.insolvent')],
                Response::HTTP_PAYMENT_REQUIRED);

        }
    }
}
