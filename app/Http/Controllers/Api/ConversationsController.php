<?php
/**
 * Created by PhpStorm.
 * User: antho
 * Date: 28/01/2018
 * Time: 11:00
 */

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request) {
        return response()
            ->json([
                'conversations' => $this->conversationsRepository->getConversations($request->user()->id)
            ]);
    }

    public function show(Request $request, User $user) {
        $messages = $this->conversationsRepository->getMessagesFor($request->user()->id, $user->id)->get();
        return [
            'messages' => $messages->reverse()
        ];
    }
}