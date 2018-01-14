<?php

namespace App\Http\Controllers;

use App\Repository\ConversationsRepository;
use App\User;
use Illuminate\Auth\AuthManager;

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
            'user' => $user
        ]);
    }
}
