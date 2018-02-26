<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class WithdrawalMoneyAlreadyNotProcessing extends Exception
{
    public function render($request)
    {
        if ($request->expectsJson()) {

            return response(['message' => trans('exceptions.bill.not_processing')],
                Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
