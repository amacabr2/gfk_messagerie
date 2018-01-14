<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Notifications\MessageReceived;
use App\Repository\ConversationsRepository;
use App\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;

class ConversationsController extends Controller {

    /**
     * @var ConversationsRepository
     */
    private $conversationsRepository;

    /**
     * @var AuthManager
     */
    private $auth;

    /**
     * ConversationsController constructor.
     * @param ConversationsRepository $conversationsRepository
     * @param AuthManager $auth
     */
    public function __construct(ConversationsRepository $conversationsRepository, AuthManager $auth) {
        $this->middleware('auth');
        $this->conversationsRepository = $conversationsRepository;
        $this->auth = $auth;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        $me = $this->auth->user();
        return view('conversations.index', [
            'users' => $this->conversationsRepository->getConversations($me->id),
            'unread' => $this->conversationsRepository->unreadCount($me->id)
        ]);
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(User $user) {
        $me = $this->auth->user();
        $unread = $this->conversationsRepository->unreadCount($me->id);

        if (isset($unread[$user->id])) {
            $this->conversationsRepository->readAllFrom($user->id, $me->id);
             unset($unread[$user->id]);
        }

        return view('conversations.show', [
            'users' => $this->conversationsRepository->getConversations($me->id),
            'user' => $user,
            'messages' => $this->conversationsRepository->getMessagesFor($me->id, $user->id)->paginate(20),
            'unread' => $unread
        ]);
    }

    /**
     * @param User $user
     * @param StoreMessageRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(User $user, StoreMessageRequest $request) {
        $message = $this->conversationsRepository->createMassage(
            $request->get('content'),
            $this->auth->user()->id,
            $user->id
        );

        $user->notify(new MessageReceived($message));

        return redirect()->route('conversations.show', ['id' => $user->id]);
    }
}
