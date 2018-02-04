<?php

namespace App\Policies;

use App\Message;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MessagePolicy {

    use HandlesAuthorization;

    /**
     * @param User $user
     * @param Message $message
     * @return bool
     */
    public function read(User $user, Message $message) {
        return $user->id === $message->to_id;
    }
}
