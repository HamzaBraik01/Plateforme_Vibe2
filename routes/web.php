<?php

use App\Http\Controllers\FriendController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/friends', [FriendController::class, 'index'])->name('friends.index');
    Route::post('/friends/{receiver_id}/request', [FriendController::class, 'sendRequest'])->name('friends.request');
    Route::post('/friends/{id}/accept', [FriendController::class, 'acceptRequest'])->name('friends.accept');
    Route::delete('/friends/{id}/reject', [FriendController::class, 'rejectRequest'])->name('friends.reject');
    Route::delete('/friends/{friend_id}/remove', [FriendController::class, 'removeFriend'])->name('friends.remove');

    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::patch('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
    Route::post('/posts/{post}/comment', [PostController::class, 'comment'])->name('posts.comment');
});

require __DIR__.'/auth.php';
