<?php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MessageController;

Route::post('register', [AuthController::class, 'register']);
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
Route::middleware(['jwt.auth'])->group(function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::get('users', [UserController::class, 'index']); // List users
    Route::get('messages/{user}', [MessageController::class, 'index']); // Get messages with a user
    Route::post('messages', [MessageController::class, 'store']); // Send message
});

Route::middleware(['jwt.auth'])->get('/users', function (Request $request) {
    return \App\Models\User::select('id', 'name', 'email', 'created_at', 'last_login_at')->get();
});