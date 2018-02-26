<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class UserBillNotExist extends Exception
{
    public function render($request)
    {
        if ($request->expectsJson()) {

            return response(['message' => trans('exceptions.user.bill')],
                Response::HTTP_NOT_FOUND);

        }
    }
}
