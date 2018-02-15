<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class MaxMasternodes extends Exception
{
    public function render($request)
    {
        if ($request->expectsJson()) {

            return response(['message' =>
                trans('exceptions.node.max', ['count' => config('masternode.max')])
            ],Response::HTTP_FORBIDDEN);

        }
    }
}
