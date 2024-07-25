<?php

use App\Http\Controllers\{UserController, AuthController};
use Illuminate\Support\Facades\Route;
use \App\Http\Middlewares\{JWTMiddleware, UserMiddleware};

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
