<?php

namespace App\Services;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserActionService
{
    /**
     * @var \Illuminate\Contracts\Auth\Authenticatable|null
     */
    private $user;

    /**
     * UserActionService constructor.
     */
    public function __construct()
    {
        $this->user = Auth::user();
    }

    /**
     * @param Request $request
     * @param string $message
     * @return bool
     */
    public function write(Request $request, string $message)
    {

        $this->user->actions()->create([
            'message' => $message,
            'ip' => $this->getIp($request),
            'agent' => $this->getAgent($request)
        ]);

        return true;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param Request $request
     * @return string
     */
    protected function getAgent(Request $request)
    {
        return $request->userAgent();
    }

    /**
     * @param Request $request
     * @return string
     */
    protected function getIp(Request $request)
    {
        return $request->ip();
    }
}