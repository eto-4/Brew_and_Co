<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Rutes públicas

// Rutes de sessió
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// Rutes productes
Route::get('/product', [ProductController::class, 'index']);

// Rutes que requereixen d'autenticació
Route::middleware('auth:sanctum')->group(function () {
    // Rutes de sessió
    Route::post('/logout',          [AuthController::class, 'logout']);

    // Rutes usuari
    Route::get('/user/profile',     [AuthController::class, 'profile']);
    Route::put('/user/profile',     [AuthController::class, 'updateProfile']);

    // Rutes de productes
    Route::get('/product/{product}', [ProductController::class, 'show']);
    
    // Rutes que requereixen ser admin:
    Route::middleware('is_admin')->group(function () {
        // Rutes de productes
        Route::put('/admin/products/{product}/toggle', [ProductController::class, 'toggleDisponible']);
    });
});

