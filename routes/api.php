<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

// Route untuk login
Route::post('/login', [AuthController::class, 'login']);

// Route untuk logout (memerlukan otentikasi)
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Route untuk mendapatkan detail user saat ini (memerlukan otentikasi)
Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');

