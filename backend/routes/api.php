<?php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\MessageController;

// Auth routes (clean rebuild)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::get('users', [UserController::class, 'index']); // List users
    Route::get('messages/{user}', [MessageController::class, 'index']); // Get messages with a user
    Route::post('messages', [MessageController::class, 'store']); // Send message
});