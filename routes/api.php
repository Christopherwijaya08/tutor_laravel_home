<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum','check.token.expiration'])->group(function (){
    Route::post('/logout', [AuthenticationController::class, 'logout']);
    Route::get('/userDetailByToken', [AuthenticationController::class, 'getUserDetailByToken']);
    
    Route::get('/posts', [PostController::class, 'index']);
    Route::get('/posts/{id}', [PostController::class, 'show']);
    Route::get('/postsNoWriter/{id}', [PostController::class, 'showWithOutWriter']);
    Route::post('/posts', [PostController::class, 'store']);
    Route::patch('/posts/{id}', [PostController::class, 'update'])->middleware('post-owner');
    Route::delete('/delete/{id}', [PostController::class, 'destroy'])->middleware('post-owner');

    Route::get('/notes', [NoteController::class, 'index']);
    Route::post('/notes', [NoteController::class, 'store']);
    Route::get('/note/{id}', [NoteController::class, 'show']);
    Route::put('/notes/{id}', [NoteController::class, 'update']);
    Route::delete('/notes/{id}', [NoteController::class, 'destroy']);

});

Route::post('/login', [AuthenticationController::class, 'login']);
Route::post('/register', [AuthenticationController::class,'register']);
