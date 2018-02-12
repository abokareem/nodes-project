<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class UnconfirmedEmailException extends Exception
{
    public function render($request)
    {
        if ($request->expectsJson()) {

            return response(['message' => trans('exceptions.emails.confirm')],
                Response::HTTP_FAILED_DEPENDENCY);

        }
    }
}
