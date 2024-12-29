<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthTokenController;

Route::middleware('guest')->group(function () {
    Route::post('/auth/login', [AuthTokenController::class, 'login']);
});

// Rutas protegidas por autenticación
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthTokenController::class, 'logout']);
});

