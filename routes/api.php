<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\CreditController;
use App\Http\Controllers\Api\SupportController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::put('/profile/password', [ProfileController::class, 'updatePassword']);

    // Product routes
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/products/search', [ProductController::class, 'search']);
    Route::get('/products/{product}', [ProductController::class, 'show']);

    // Order routes
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::delete('/orders/{order}', [OrderController::class, 'cancel']);

    // Credit routes
    Route::get('/credits/balance', [CreditController::class, 'balance']);
    Route::get('/credits/transactions', [CreditController::class, 'transactions']);

    // Support routes
    Route::get('/tickets', [SupportController::class, 'index']);
    Route::post('/tickets', [SupportController::class, 'store']);
    Route::get('/tickets/{ticket}', [SupportController::class, 'show']);
    Route::post('/tickets/{ticket}/respond', [SupportController::class, 'respond']);
}); 

// Fallback route for unmatched API requests
Route::fallback(function(){
    \Log::warning('Unmatched API Route:', [
        'method' => request()->method(),
        'path' => request()->path(),
        'ip' => request()->ip(),
        'user_agent' => request()->userAgent(),
    ]);
    return response()->json([
        'message' => 'API Endpoint not found.',
    ], 404);
}); 