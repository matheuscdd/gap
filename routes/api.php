<?php

use App\Http\Controllers\{
    UserController,
    AuthController,
    BudgetController,
    ClientController,
    StockTypeController,
    DeliveryController,
    TruckController,
    MaintenanceController,
    DriverController,
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
    MaintenanceMiddleware,
    DriverMiddleware,
};

Route::prefix('auth')->group(function() {
    Route::post('/login', [AuthController::class, 'login']);
    Route::put('/refresh', [AuthController::class, 'refresh']);
});

Route::prefix('users')->group(function() {
    Route::post('/password/lost', [UserController::class, 'lostPassword']);
    Route::put('/password/reset', [UserController::class, 'resetPassword']);
});

Route::prefix('users')->middleware([JWTMiddleware::class, UserMiddleware::class])->group(
    function() {
    Route::post('/', [UserController::class, 'create']);
    Route::get('/', [UserController::class, 'list']);
    Route::get('/{id}', [UserController::class, 'find']);
    Route::patch('/{id}', [UserController::class, 'edit']);
    Route::delete('/{id}', [UserController::class, 'del']);
});

Route::prefix('clients')->middleware([JWTMiddleware::class, ClientMiddleware::class])->group(function() {
    Route::post('/', [ClientController::class, 'create']);
    Route::get('/', [ClientController::class, 'list']);
    Route::get('/{id}', [ClientController::class, 'find']);
    Route::patch('/{id}', [ClientController::class, 'edit']);
    Route::delete('/{id}', [ClientController::class, 'del']);
});

Route::prefix('stock_type')->middleware([JWTMiddleware::class, StockTypeMiddleware::class])->group(function() {
    Route::post('/', [StockTypeController::class, 'create']);
    Route::get('/', [StockTypeController::class, 'list']);
    Route::patch('/{id}', [StockTypeController::class, 'edit']);
});

Route::prefix('budgets')->middleware([JWTMiddleware::class, BudgetMiddleware::class])->group(function() {
    Route::post('/', [BudgetController::class, 'create']);
    Route::get('/', [BudgetController::class, 'list']);
    Route::get('/{id}', [BudgetController::class, 'find']);
    Route::patch('/{id}', [BudgetController::class, 'edit']);
    Route::delete('/{id}', [BudgetController::class, 'del']);
});

Route::prefix('deliveries')->middleware([JWTMiddleware::class, DeliveryMiddleware::class])->group(function() {
    Route::post('/full', [DeliveryController::class, 'createFull']);
    Route::get('/full', [DeliveryController::class, 'listFull']);
    Route::get('/full/{id}', [DeliveryController::class, 'findFull']);
    Route::delete('/full/{id}', [DeliveryController::class, 'delFull']);
    Route::patch('/full/finish/{id}', [DeliveryController::class, 'finishFull']);
    Route::patch('/full/{id}', [DeliveryController::class, 'editFull']);
    Route::put('/partial/{id}', [DeliveryController::class, 'createPartial']);
    Route::get('/partial/{id}', [DeliveryController::class, 'listPartial']);
    Route::delete('/partial/{id}', [DeliveryController::class, 'delPartial']);
    Route::patch('/partial/finish/{id}', [DeliveryController::class, 'finishPartial']);
    Route::get('/calendar', [DeliveryController::class, 'calendar']);
    Route::get('/charts/treemap', [DeliveryController::class, 'chartsTreemap']);
    Route::get('/charts/scatter', [DeliveryController::class, 'chartsScatter']);
});

Route::prefix('trucks')->middleware([JWTMiddleware::class, TruckMiddleware::class])->group(function() {
    Route::post('/', [TruckController::class, 'create']);
    Route::get('/', [TruckController::class, 'list']);
    Route::patch('/{id}', [TruckController::class, 'edit']);
    Route::get('/{id}', [TruckController::class, 'find']);
    Route::delete('/{id}', [TruckController::class, 'del']);
});

Route::prefix('maintenances')->middleware([JWTMiddleware::class, MaintenanceMiddleware::class])->group(function() {
    Route::post('/', [MaintenanceController::class, 'create']);
    Route::get('/', [MaintenanceController::class, 'list']);
    Route::patch('/{id}', [MaintenanceController::class, 'edit']);
    Route::get('/{id}', [MaintenanceController::class, 'find']);
    Route::delete('/{id}', [MaintenanceController::class, 'del']);
    Route::get('/charts/scatter', [MaintenanceController::class, 'chartsScatter']);
});

Route::prefix('drivers')->middleware([JWTMiddleware::class, DriverMiddleware::class])->group(function() {
    Route::post('/', [DriverController::class, 'create']);
    Route::get('/', [DriverController::class, 'list']);
    Route::patch('/{id}', [DriverController::class, 'edit']);
    Route::get('/{id}', [DriverController::class, 'find']);
    Route::delete('/{id}', [DriverController::class, 'del']);
});