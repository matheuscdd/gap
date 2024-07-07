<?php

use App\Http\Controllers\{UserController, AuthController};
use Illuminate\Support\Facades\Route;
use \App\Http\Middlewares\JWTMiddleware;


Route::group([
    'prefix' => 'auth'
], function() {
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware([JWTMiddleware::class])->group(function() {
    Route::post('/users', [UserController::class, 'create']);
    Route::get('/users/{id}', [UserController::class, 'get']);
    Route::patch('/users/{id}', [UserController::class, 'edit']);
});
