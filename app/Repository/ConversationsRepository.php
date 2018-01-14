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
     * Get conversations for user's connected
     *
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
     * Create a message for the conversation
     *
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
     * Retrieves the messages corresponding to the conversation
     *
     * @param int $from
     * @param int $to
     * @return Builder
     */
    public function getMessagesFor(int $from, int $to): Builder {
        return $this->message->newQuery()
            ->whereRaw("((from_id = $from AND to_id = $to) OR (from_id = $to AND to_id = $from))")
            ->orderBy('created_at', 'DESC')
            ->with([
                'from' => function($query) {
                    return $query->select('name', 'id');
                }
            ]);
    }

    /**
     *
     * Get the number of unread messages for each conversation
     *
     * @param int $userId
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public function unreadCount(int $userId) {
        return $this->message->newQuery()
            ->where('to_id', $userId)
            ->groupBy('from_id')
            ->selectRaw('from_id, COUNT(id) AS count')
            ->whereRaw('read_at IS NULL')
            ->get()
            ->pluck('count', 'from_id');
    }

    /**
     * Mark all user's messages as read
     *
     * @param int $from
     * @param int $to
     */
    public function readAllFrom(int $from, int $to) {
        $this->message
            ->where('from_id', $from)
            ->where('to_id', $to)
            ->update(['read_at' => Carbon::now()]);
    }
}