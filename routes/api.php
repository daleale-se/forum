<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\CommentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::put('/threads/{id}/add_like', [ThreadController::class, 'add_like'])->name('threads.add_like');
Route::delete('/threads/{id}', [ThreadController::class, 'destroy'])->name('threads.destroy');
Route::post('/threads/{id}/comments', [CommentController::class, 'store'])->name('comments.store');
