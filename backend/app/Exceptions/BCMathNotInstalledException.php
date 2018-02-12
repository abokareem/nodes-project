<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class BCMathNotInstalledException extends Exception
{
    public function render($request)
    {
        if ($request->expectsJson()) {

            return response(['message' =>
                trans('exceptions.server.extension', ['extension' => 'bcmath'])
            ],Response::HTTP_INTERNAL_SERVER_ERROR);

        }
    }
}
