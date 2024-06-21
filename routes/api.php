<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum','check.token.expiration'])->group(function (){
    Route::get('/posts', [PostController::class, 'index']);
    Route::get('/posts/{id}', [PostController::class, 'show']);
    Route::get('/postsNoWriter/{id}', [PostController::class, 'showWithOutWriter']);
    Route::post('/logout', [AuthenticationController::class, 'logout']);
    Route::get('/userDetailByToken', [AuthenticationController::class, 'getUserDetailByToken']);
    
    Route::post('/posts', [PostController::class, 'store']);
    Route::patch('/posts/{id}', [PostController::class, 'update'])->middleware('post-owner');
    Route::delete('/delete/{id}', [PostController::class, 'destroy'])->middleware('post-owner');
});

Route::post('/login', [AuthenticationController::class, 'login']);
Route::post('/register', [AuthenticationController::class,'register']);
