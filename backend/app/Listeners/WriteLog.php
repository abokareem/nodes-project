<?php

namespace App\Listeners;

use App\Events\ResetPassword;
use App\Services\UserActionService;
use Illuminate\Http\Request;

class WriteLog
{
    private $request;
    private $log;

    /**
     * ResetPasswordWriteLog constructor.
     * @param Request $request
     * @param UserActionService $actionService
     */
    public function __construct(Request $request, UserActionService $actionService)
    {
        $this->request = $request;
        $this->log = $actionService;
    }

    /**
     * Handle the event.
     *
     * @param  ResetPassword $event
     * @return void
     */
    public function handle($event)
    {
        if ($event->user) {
            $this->log->setUser($event->user);
        }

        $this->log->write($this->request, $event->message);
    }
}
