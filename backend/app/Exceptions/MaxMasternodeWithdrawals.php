<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class MaxMasternodeWithdrawals extends Exception
{
    public function render($request)
    {
        if ($request->expectsJson()) {

            return response(['message' => trans('exceptions.withdrawal.max')],
                Response::HTTP_CONFLICT);

        }
    }
}
