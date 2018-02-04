<?php
/**
 * Created by PhpStorm.
 * User: antho
 * Date: 28/01/2018
 * Time: 11:00
 */

namespace App\Http\Controllers\Api;


use App\Events\NewMessageEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMessageRequest;
use App\Message;
use App\Repository\ConversationsRepository;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ConversationsController extends Controller {

    /**
     * @var ConversationsRepository
     */
    private $conversationsRepository;

    /**
     * ConversationsController constructor.
     * @param ConversationsRepository $conversationsRepository
     */
    public function __construct(ConversationsRepository $conversationsRepository) {
        $this->conversationsRepository = $conversationsRepository;
    }

    /**
     * Retrieves the user's conversations
     * @param Request $request
     * @return array
     */
    public function index(Request $request) {
        $conversations = $this->conversationsRepository->getConversations($request->user()->id);
        $unread = $this->conversationsRepository->unreadCount($request->user()->id);

        foreach ($conversations as $conversation) {
            if (isset($unread[$conversation->id])) {
                $conversation->unread = $unread[$conversation->id];
            } else {
                $conversation->unread = 0;
            }
        }

        return [
            'conversations' => $conversations
        ];
    }

    /**
     * Show messages for one conversation
     * @param Request $request
     * @param User $user
     * @return array
     */
    public function show(Request $request, User $user) {
        $messagesQuery = $this->conversationsRepository->getMessagesFor($request->user()->id, $user->id);
        $count = null;

        if($before = $request->get('before')) {
            $messagesQuery = $messagesQuery->where('created_at', '<', $request->get('before'));
        } else {
            $count = $messagesQuery->count();
        }

        $messages = $messagesQuery->limit(10)->get();
        $update = false;

        foreach ($messages as $message) {
            if ($message->read_at === null && $message->to_id === $request->user()->id) {
                $message->read_at = Carbon::now();
                if ($update === false) {
                    $this->conversationsRepository->readAllFrom($message->from_id, $message->to_id);
                }
                $update = true;
            }
        }

        return [
            'messages' => array_reverse($messages->toArray()),
            'count' => $count
        ];
    }

    /**
     * Send a new message in the current conversation
     * @param User $user
     * @param StoreMessageRequest $request
     * @return array
     */
    public function store(User $user, StoreMessageRequest $request) {
        /** @var Message $message */
        $message = $this->conversationsRepository->createMassage(
            $request->get('content'),
            $request->user()->id,
            $user->id
        );

        broadcast(new NewMessageEvent($message));

        return [
            'message' => $message
        ];
    }
}