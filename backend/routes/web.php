<?php

// use App\Http\Controllers\AuthController;
// use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::post('/api/register', [AuthController::class, 'register']);
// Route::post('/api/login', [AuthController::class, 'login']);
// Route::middleware(['jwt.auth'])->group(function () {
//     Route::get('/api/me', [AuthController::class, 'me']);
// });

Route::get('/login', function () {
    return response()->json(['message' => 'Login page placeholder.'], 401);
})->name('login');
