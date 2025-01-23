<?php

use App\Http\Controllers\{
    UserController,
    AuthController,
    BudgetController,
    ClientController,
    StockTypeController,
    DeliveryController,
    TruckController,
};
use Illuminate\Support\Facades\Route;
use \App\Http\Middlewares\{
    BudgetMiddleware,
    DeliveryMiddleware,
    ClientMiddleware,
    JWTMiddleware,
    StockTypeMiddleware,
    UserMiddleware,
    TruckMiddleware,
};

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
    Route::delete('/clients/{id}', [ClientController::class, 'del']);
});

Route::middleware([JWTMiddleware::class, StockTypeMiddleware::class])->group(function() {
    Route::post('/stock_type', [StockTypeController::class, 'create']);
    Route::get('/stock_type', [StockTypeController::class, 'list']);
    Route::patch('/stock_type/{id}', [StockTypeController::class, 'edit']);
});

Route::middleware([JWTMiddleware::class, BudgetMiddleware::class])->group(function() {
    Route::post('/budgets', [BudgetController::class, 'create']);
    Route::get('/budgets', [BudgetController::class, 'list']);
    Route::get('/budgets/{id}', [BudgetController::class, 'find']);
    Route::patch('/budgets/{id}', [BudgetController::class, 'edit']);
    Route::delete('/budgets/{id}', [BudgetController::class, 'del']);
});

Route::middleware([JWTMiddleware::class, DeliveryMiddleware::class])->group(function() {
    Route::post('/deliveries/full', [DeliveryController::class, 'createFull']);
    Route::get('/deliveries/full', [DeliveryController::class, 'listFull']);
    Route::get('/deliveries/full/{id}', [DeliveryController::class, 'findFull']);
    Route::delete('/deliveries/full/{id}', [DeliveryController::class, 'delFull']);
    Route::patch('/deliveries/full/finish/{id}', [DeliveryController::class, 'finishFull']);
    Route::patch('/deliveries/full/{id}', [DeliveryController::class, 'editFull']);
    Route::put('/deliveries/partial/{id}', [DeliveryController::class, 'createPartial']);
    Route::get('/deliveries/partial/{id}', [DeliveryController::class, 'listPartial']);
    Route::delete('/deliveries/partial/{id}', [DeliveryController::class, 'delPartial']);
    Route::patch('/deliveries/partial/finish/{id}', [DeliveryController::class, 'finishPartial']);
    Route::get('/deliveries/treemap', [DeliveryController::class, 'treemap']);
    Route::get('/deliveries/calendar', [DeliveryController::class, 'calendar']);
});

Route::middleware([JWTMiddleware::class, TruckMiddleware::class])->group(function() {
    Route::post('/trucks', [TruckController::class, 'create']);
    Route::get('/trucks', [TruckController::class, 'list']);
    Route::patch('/trucks/{id}', [TruckController::class, 'edit']);
    Route::get('/trucks/{id}', [TruckController::class, 'find']);
});