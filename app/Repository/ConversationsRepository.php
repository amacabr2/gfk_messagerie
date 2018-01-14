<?php
/**
 * Created by PhpStorm.
 * User: antho
 * Date: 14/01/2018
 * Time: 11:12
 */

namespace App\Repository;

use App\User;

class ConversationsRepository {

    /**
     * @var User
     */
    private $user;

    /**
     * ConversationsRepository constructor.
     * @param User $user
     */
    public function __construct(User $user) {
        $this->user = $user;
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
}