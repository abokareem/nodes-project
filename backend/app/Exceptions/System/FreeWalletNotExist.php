<?php

namespace App\Exceptions\System;

use Exception;
use Illuminate\Http\Response;

class FreeWalletNotExist extends Exception
{
    public function render($request)
    {
        if ($request->expectsJson()) {

            return response(['message' => trans('exceptions.user.free_wallet')],
                Response::HTTP_NOT_FOUND);

        }
    }
}
