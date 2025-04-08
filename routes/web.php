<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ThreadController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [ThreadController::class, 'index'])->name('threads.home');
Route::get('/threads', [ThreadController::class, 'index'])->name('threads.index');
Route::get('/threads/{id}', [ThreadController::class, 'thread_page'])->name('threads.show');
Route::post('/threads', [ThreadController::class, 'store'])->name('threads.store');
Route::get('/form', [ThreadController::class, 'thread_form'])->name('threads.form');