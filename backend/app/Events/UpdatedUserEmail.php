<?php

namespace App\Events;

use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class UpdatedUserEmail
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $tries;
    public $message;

    /**
     * Create a new event instance.
     *
     * @param User $user
     * @param int $tries
     */
    public function __construct(User $user, int $tries = 0)
    {
        $this->user = $user;
        $this->tries = $tries;
    }
}
