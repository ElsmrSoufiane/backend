<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Authcontroller;
use App\Http\Controllers\costumercontroller;



Route::options('/{any}', function () {
    return response()->json([], 200, [
        'Access-Control-Allow-Origin' => 'http://localhost:3000',
        'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
        'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With',
        'Access-Control-Max-Age' => '86400',
    ]);
})->where('any', '.*'); 



// Auth Routes (direct routes)
Route::post('/register', [Authcontroller::class, 'register']);
Route::post('/login', [Authcontroller::class, 'login']);
Route::post('/logout', [Authcontroller::class, 'logout']);

// Customer Routes (direct routes)
Route::get('/customers', [costumercontroller::class, 'index']);
Route::get('/customers/{id}', [costumercontroller::class, 'show']);
Route::get('/customers/{id}/motifs', [costumercontroller::class, 'motifs']);
Route::post('/customers', [costumercontroller::class, 'store']);
Route::put('/customers/{id}', [costumercontroller::class, 'updateComment']);
Route::delete('/customers/{id}', [costumercontroller::class, 'deleteComment']);