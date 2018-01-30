<?php
/**
 * Created by PhpStorm.
 * User: antho
 * Date: 28/01/2018
 * Time: 11:00
 */

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMessageRequest;
use App\Repository\ConversationsRepository;
use App\User;
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
        return [
            'conversations' => $this->conversationsRepository->getConversations($request->user()->id)
        ];
    }

    /**
     * Show messages for one conversation
     * @param Request $request
     * @param User $user
     * @return array
     */
    public function show(Request $request, User $user) {
        $messages = $this->conversationsRepository->getMessagesFor($request->user()->id, $user->id)->get();
        return [
            'messages' => array_reverse($messages->toArray())
        ];
    }

    /**
     * Send a new message in the current conversation
     * @param User $user
     * @param StoreMessageRequest $request
     * @return array
     */
    public function store(User $user, StoreMessageRequest $request) {
        $message = $this->conversationsRepository->createMassage(
            $request->get('content'),
            $request->user()->id,
            $user->id
        );

        return [
            'message' => $message
        ];
    }
}