<?php

use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\EpicController;
use App\Http\Controllers\TaskCommentController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('/auth')->group(function(){
    Route::post('/register', [AuthenticationController::class, 'register'])->name('auth.register');
    Route::post('/login', [AuthenticationController::class, 'login'])->name('auth.login');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('/epic', EpicController::class)->names('epic');
    Route::resource('/task', TaskController::class)->names('task');
    Route::resource('/task/comment', TaskCommentController::class)->only([
        'store',
        'update',
        'destroy',
    ])->names('task-comment');
});
