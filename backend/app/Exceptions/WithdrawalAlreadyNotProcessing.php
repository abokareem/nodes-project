<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class WithdrawalAlreadyNotProcessing extends Exception
{
    public function render($request)
    {
        if ($request->expectsJson()) {

            return response(['message' => trans('exceptions.withdrawal.not_processing')],
                Response::HTTP_BAD_REQUEST);

        }
    }
}
