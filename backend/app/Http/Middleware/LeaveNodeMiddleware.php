<?php

namespace App\Http\Middleware;

use App\Exceptions\MaxMasternodeWithdrawals;
use App\Masternode;
use App\Withdrawals;
use Carbon\Carbon;
use Closure;

class LeaveNodeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \App\Http\Requests\Api\LeaveNodeRequest $request
     * @param  \Closure $next
     * @return mixed
     * @throws MaxMasternodeWithdrawals
     */
    public function handle($request, Closure $next)
    {
        $node = Masternode::find($request->get('node_id'));

        if ($node) {
            $createdTime = Withdrawals::where('node_id', $node->id)
                ->latest()->first()->created_at;
            if ($createdTime->addHours(24)->lt(Carbon::now())) {
                throw new MaxMasternodeWithdrawals();
            }

        }
        return $next($request);
    }
}
