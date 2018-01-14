<?php
/**
 * Created by PhpStorm.
 * User: antho
 * Date: 14/01/2018
 * Time: 11:12
 */

namespace App\Repository;

use App\Message;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class ConversationsRepository {

    /**
     * @var User
     */
    private $user;

    /**
     * @var Message
     */
    private $message;

    /**
     * ConversationsRepository constructor.
     * @param User $user
     * @param Message $message
     */
    public function __construct(User $user, Message $message) {
        $this->user = $user;
        $this->message = $message;
    }

    /**
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getConversations(int $userId) {
        return $this->user->newQuery()
            ->select('name', 'id')
            ->where('id', '!=', $userId)
            ->get();
    }

    /**
     * @param string $content
     * @param int $from
     * @param int $to
     * @return $this|\Illuminate\Database\Eloquent\Model
     */
    public function createMassage(string $content, int $from, int $to) {
        return $this->message->newQuery()->create([
            'content' => $content,
            'from_id' => $from,
            'to_id' => $to,
            'created_at' => Carbon::now()
        ]);
    }


    /**
     * @param int $from
     * @param int $to
     * @return Builder
     */
    public function getMessagesFor(int $from, int $to): Builder {
        return $this->message->newQuery()
            ->whereRaw("((from_id = $from AND to_id = $to) OR (from_id = $to AND to_id = $from))")
            ->orderBy('created_at', 'DESC');
    }
}