<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\Admin\AIAssistantController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

// Rutes públicas

// Rutes de sessió
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// Rutes productes
Route::get('/product', [ProductController::class, 'index']);

Route::get('/categories', [ProductController::class, 'categories']);

// Rutes Ofertes
Route::get('/offers', [OfferController::class, 'index']);

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

    // Rutes Pagaments
    Route::post('/payments/{order}', [PaymentController::class, 'process']);

    // Rutes Codis Descompte
    Route::post('/discounts/validate', [DiscountController::class, 'validate']);

    // Rutes que requereixen ser admin:
    Route::middleware('is_admin')->prefix('admin')->group(function () {

        // Rutes de productes
        Route::put('/products/{product}/toggle', [ProductController::class, 'toggleDisponible']);

        // Rutes ordres
        Route::get('/orders', [OrderController::class, 'indexAll']);

        // Rutes Pagaments
        Route::patch('/payments/{payment}/force', [PaymentController::class, 'forceStatus']);

        // Rutes Ofertes
        Route::post('/offers', [OfferController::class, 'store']);
        Route::put('/offers/{offer}', [OfferController::class, 'update']);
        Route::delete('/offers/{offer}', [OfferController::class, 'destroy']);

        // Rutes codis descompte
        Route::post('/discounts', [DiscountController::class, 'store']);
        Route::put('/discounts/{discountCode}', [DiscountController::class, 'update']);

        // Administracio:
        Route::get('/dashboard', [DashboardController::class, 'index']);
        Route::get('/export/{format}', [DashboardController::class, 'export']);

        // Ruta AI Chat
        Route::post('/ai/chat', [AIAssistantController::class, 'chat']);
    });
});

