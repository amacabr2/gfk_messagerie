<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
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
        $this->conversationsRepository = $conversationsRepository;
        $this->auth = $auth;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        return view('conversations.index', [
            'users' => $this->conversationsRepository->getConversations($this->auth->user()->id)
        ]);
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(User $user) {
        return view('conversations.show', [
            'users' => $this->conversationsRepository->getConversations($this->auth->user()->id),
            'user' => $user,
            'messages' => $this->conversationsRepository->getMessagesFor($this->auth->user()->id, $user->id)->paginate(20)
        ]);
    }

    /**
     * @param User $user
     * @param StoreMessageRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(User $user, StoreMessageRequest $request) {
        $this->conversationsRepository->createMassage(
            $request->get('content'),
            $this->auth->user()->id,
            $user->id
        );

        return redirect()->route('conversations.show', ['id' => $user->id]);
    }
}
