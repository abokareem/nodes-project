<?php

namespace App\Http\Middleware;

use App\Exceptions\MaxMasternodes;
use App\Masternode;
use Closure;

class MasternodeCreated
{
    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     * @throws MaxMasternodes
     */
    public function handle($request, Closure $next)
    {
        $masternode = Masternode::where([
            ['currency_id', $request->get('currency_id')],
            ['state', Masternode::NEW_STATE]
        ])->count();

        if ($masternode >= config('masternode.max')) {
            throw new MaxMasternodes();
        }

        return $next($request);
    }
}
