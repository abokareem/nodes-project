<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class MaxSentUserRegisteredEmails extends Exception
{
    public function render($request)
    {
        if ($request->expectsJson()) {

            return response(['message' => trans('exceptions.emails.registered')],
                Response::HTTP_LOCKED);

        }
    }
}
