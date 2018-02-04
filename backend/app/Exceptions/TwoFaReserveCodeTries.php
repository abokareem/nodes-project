<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class TwoFaReserveCodeTries extends Exception
{
    public function render($request)
    {
        if ($request->expectsJson()) {

            return response(['message' => 'Your account is blocked. Please contact support.'],
                Response::HTTP_FORBIDDEN);

        }
    }
}
