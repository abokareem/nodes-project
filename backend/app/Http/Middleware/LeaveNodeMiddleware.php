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

        if (!$node) {
            return $next($request);
        }

        $withdrawal = Withdrawals::where('node_id', $node->id)
            ->latest()->first();

        if (!$withdrawal) {
            return $next($request);
        }

        if ($withdrawal->created_at->addHours(24)->gt(Carbon::now())) {
            throw new MaxMasternodeWithdrawals();
        }

        return $next($request);
    }
}
