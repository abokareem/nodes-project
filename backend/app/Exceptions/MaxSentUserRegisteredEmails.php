<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class MaxSentUserRegisteredEmails extends Exception
{
    public function render($request)
    {
        if ($request->expectsJson()) {

            return response(['message' => 'You have sent the maximum number of mail'],
                Response::HTTP_LOCKED);

        }
    }
}
