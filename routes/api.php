<?php

use App\Http\Controllers\Api\Auth\ApiAuthController;
use App\Http\Controllers\Api\User\CommentController;
use App\Http\Controllers\Api\User\ContentController;
use App\Http\Controllers\Api\User\FollowerController;
use App\Http\Controllers\Api\User\LikeController;
use App\Http\Controllers\Api\User\ProfileController;
use App\Http\Controllers\Api\User\UserController;
use Illuminate\Support\Facades\Route;


Route::post('/auth/login', [ApiAuthController::class, 'login'])->name('login.api');
Route::post('/auth/register', [ApiAuthController::class, 'register'])->name('register.api');
Route::middleware('auth:api')->prefix('auth')->group(function () {

    Route::resource('users', UserController::class);

    Route::resource('profiles', ProfileController::class);

    Route::resource('contents', ContentController::class);

    Route::resource('likes', LikeController::class);

    Route::resource('comments', CommentController::class);

    Route::resource('followers', FollowerController::class);
});
