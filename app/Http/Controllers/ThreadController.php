<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Thread;

class ThreadController extends Controller
{

    public function index() {
        $threads = Thread::all();

        return view('index', ['threads' => $threads]);
    }

    public function store(Request $request) {
        Thread::create([
            'title' => $request->title,
            'body' => $request->body,
            'category' => $request->category,
        ]);

        return response('/', 302);
    }

    public function thread_page($id) {
        $thread = Thread::find($id);

        return view('thread', ['thread' => $thread]);
    }

}
