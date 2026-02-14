<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CostumerController;
use App\Http\Controllers\mailing;

// CORS preflight
Route::options('/{any}', function () {
    return response()->json([], 200, [
        'Access-Control-Allow-Origin' => 'http://localhost:3000',
        'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
        'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With',
        'Access-Control-Allow-Credentials' => 'true',
        'Access-Control-Max-Age' => '86400',
    ]);
})->where('any', '.*');

// Auth Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/verify-reset-token', [AuthController::class, 'verifyResetToken']);
Route::post('/check-email', [AuthController::class, 'checkEmail']);

// Customer Routes
Route::get('/customers', [CostumerController::class, 'index']);
Route::get('/customers/{id}', [CostumerController::class, 'show']);
Route::get('/customers/{id}/motifs', [CostumerController::class, 'motifs']);
Route::post('/customers', [CostumerController::class, 'store']);
Route::put('/customers/{id}', [CostumerController::class, 'updateComment']);
Route::delete('/customers/{id}', [CostumerController::class, 'deleteComment']);

// Email Verification Routes
Route::post('/send-verification-email', [mailing::class, 'sendVerificationEmail']);
Route::get('/verify-email/{token}', [mailing::class, 'verifyEmail']);
Route::post('/check-verification', [mailing::class, 'checkVerification']);