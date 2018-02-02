<?php

namespace App\Exceptions;

use Exception;

class TwoFaSecretNotExists extends Exception
{
    public function render($request)
    {
        if ($request->expectsJson()) {

            return response(['message' => 'something wrong'],500);

        }

        return parent::render($request, $this);
    }
}
