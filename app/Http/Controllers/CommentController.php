<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\TemporalUser;

class CommentController extends Controller
{
    public function store(Request $request) {

        $temporalUserId = session('temporal_user_id');

        if (!$temporalUserId) {
            $temporalUser = TemporalUser::create([
                'assigned_username' => TemporalUser::generateUsername(),
            ]);
            session(['temporal_user_id' => $temporalUser->id]);
            $temporalUserId = $temporalUser->id;
        }
    
        Comment::create([
            'body' => $request->body,
            'thread_id' => $request->route('id'),
            'temporal_user_id' => $temporalUserId,
        ]);
    
        return response()->json(data: ['message' => 'Comment created successfully'], status: 200);
    }

}
