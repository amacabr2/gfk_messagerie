<?php

namespace App\Http\Controllers;

class ConversationsController extends Controller {

    public function index() {
        return view('conversations.index');
    }

    public function show(int $id) {

    }
}
