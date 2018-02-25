<?php

namespace App\Exceptions\System;

use Exception;
use Illuminate\Http\Response;

class FreeWalletsLengthException extends Exception
{
    public function render($request)
    {
        if ($request->expectsJson()) {

            return response(['message' => trans('exceptions.admin.wallets')],
                Response::HTTP_UNPROCESSABLE_ENTITY);

        }
    }
}
