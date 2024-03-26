<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function (){
    Route::get('/posts', [PostController::class, 'index']);
    Route::get('/posts/{id}', [PostController::class, 'show']);
    Route::get('/postsNoWriter/{id}', [PostController::class, 'showWithOutWriter']);
    Route::get('/logout', [AuthenticationController::class, 'logout']);
    Route::get('/userDetailByToken', [AuthenticationController::class, 'getUserDetailByToken']);
});

Route::post('/login', [AuthenticationController::class, 'login']);

Route::post('/posts2', [PostController::class, 'store']);
