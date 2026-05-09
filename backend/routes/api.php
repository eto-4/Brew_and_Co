<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AddressController;
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
    
    // Rutes de Ordres
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::put('/orders/{order}', [OrderController::class, 'update']);
    Route::delete('/orders/{order}', [OrderController::class, 'cancel']);
    
    // Rutes de modificacio de direcció
    Route::get('/user/adresses', [AddressController::class, 'index']);
    Route::post('/user/adresses', [AddressController::class, 'store']);
    Route::put('/user/adresses/{adreca}', [AddressController::class, 'update']);
    Route::delete('/user/adresses/{adreca}', [AddressController::class, 'destroy']);
    Route::patch('/user/adresses/{adreca}/predeterminada', [AddressController::class, 'setPredeterminada']);

    // Rutes que requereixen ser admin:
    Route::middleware('is_admin')->group(function () {
        // Rutes de productes
        Route::put('/admin/products/{product}/toggle', [ProductController::class, 'toggleDisponible']);

        // Rutes ordres
        Route::get('/admin/orders', [OrderController::class, 'indexAll']);
    });
});

