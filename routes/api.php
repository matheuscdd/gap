<?php

use App\Http\Controllers\{UserController, AuthController, ClientController, StockTypeController};
use Illuminate\Support\Facades\Route;
use \App\Http\Middlewares\{ClientMiddleware, JWTMiddleware, StockTypeMiddleware, UserMiddleware};

Route::group([
    'prefix' => 'auth'
], function() {
    Route::post('/login', [AuthController::class, 'login']);
    Route::put('/refresh', [AuthController::class, 'refresh']);
});

Route::middleware([JWTMiddleware::class, UserMiddleware::class])->group(function() {
    Route::post('/users', [UserController::class, 'create']);
    Route::get('/users', [UserController::class, 'list']);
    Route::get('/users/{id}', [UserController::class, 'find']);
    Route::patch('/users/{id}', [UserController::class, 'edit']);
    Route::delete('/users/{id}', [UserController::class, 'del']);
});

Route::middleware([JWTMiddleware::class, ClientMiddleware::class])->group(function() {
    Route::post('/clients', [ClientController::class, 'create']);
    Route::get('/clients', [ClientController::class, 'list']);
    Route::get('/clients/{id}', [ClientController::class, 'find']);
    Route::patch('/clients/{id}', [ClientController::class, 'edit']);
});

Route::middleware([JWTMiddleware::class, StockTypeMiddleware::class])->group(function() {
    Route::post('/stock_type', [StockTypeController::class, 'create']);
    Route::get('/stock_type', [StockTypeController::class, 'list']);
    Route::patch('/stock_type/{id}', [StockTypeController::class, 'edit']);
});
