<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/hello', function () {
    return response()->json([
        'message' => 'Welcome to the Laravel 13 API',
    ]);
});

Route::post('/register', [AuthController::class, 'register'])
    ->middleware('throttle:6,1');

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:6,1');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::middleware(['role:admin,student'])->get('/students', function () {
        return response()->json([
            'message' => 'List of students',
            'students' => [
                ['id' => 1, 'name' => 'Juan Dela Cruz', 'email' => 'juan@example.com', 'role' => 'student'],
                ['id' => 2, 'name' => 'Maria Clara', 'email' => 'maria@example.com', 'role' => 'student'],
            ],
        ]);
    });

    Route::middleware(['admin'])->get('/admin/dashboard', function () {
        return response()->json([
            'message' => 'Welcome Admin!',
        ]);
    });

    Route::middleware(['role:student'])->get('/student/dashboard', function () {
        return response()->json([
            'message' => 'Welcome Student!',
        ]);
    });
});
