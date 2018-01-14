<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;

class ConversationsController extends Controller {

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        $users = User::select('name', 'id')->where('id', '!=', Auth::id())->get();
        return view('conversations.index', compact("users"));
    }

    public function show(int $id) {

    }
}
