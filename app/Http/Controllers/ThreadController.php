<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Thread;
use App\Models\TemporalUser;
use Illuminate\Support\Str;

class ThreadController extends Controller
{
    private function generateUsername(): string{
        return 'user' . Str::random(6);
    }
    
    public function index(Request $request) {
        $category = $request->query('category');
    
        $threads = $category ? Thread::where('category', $category)->get() : Thread::all();
    
        return view('index', ['threads' => $threads]);
    }
    
    public function store(Request $request) {

        $temporalUserId = session('temporal_user_id');

        if (!$temporalUserId) {
            $temporalUser = TemporalUser::create([
                'assigned_username' => $this->generateUsername(),
            ]);
            session(['temporal_user_id' => $temporalUser->id]);
            $temporalUserId = $temporalUser->id;
        }
    
        $thread = Thread::create([
            'title' => $request->title,
            'body' => $request->body,
            'category' => $request->category,
            'temporal_user_id' => $temporalUserId,
        ]);
    
        return redirect('/');
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
