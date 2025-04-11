<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Thread;
use App\Models\TemporalUser;

class ThreadController extends Controller
{
    
    public function index(Request $request) {
        $category = $request->query('category');
    
        $threads = $category ? Thread::where('category', $category)->get() : Thread::all();
    
        return view('index', ['threads' => $threads]);
    }
    
    public function store(Request $request) {
        $thread = Thread::create([
            'title' => $request->title,
            'body' => $request->body,
            'category' => $request->category,
        ]);

        TemporalUser::create([
            'thread_id' => $thread['id'],
            'assigned_username' => "ale"
        ]);

        return redirect('/', 302);
    }

    public function thread_page($id) {
        $thread = Thread::find($id);
        return view('thread', ['thread' => $thread]);
    }

    public function destroy($id) {
        Thread::findOrFail($id)->delete();
        return response()->json(data: ['message' => 'Thread deleted'], status: 200);
    }

    public function thread_form() {
        return view('form');
    }

    public function add_like($id){
        $thread = Thread::find($id);
        $thread->increment('likes');
        return response()->json(['likes' => $thread->likes], 200);
    }

}
