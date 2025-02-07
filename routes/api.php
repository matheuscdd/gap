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
    Route::get('/deliveries/calendar', [DeliveryController::class, 'calendar']);
    Route::get('/deliveries/charts/treemap', [DeliveryController::class, 'chartsTreemap']);
    Route::get('/deliveries/charts/scatter', [DeliveryController::class, 'chartsScatter']);
});

Route::middleware([JWTMiddleware::class, TruckMiddleware::class])->group(function() {
    Route::post('/trucks', [TruckController::class, 'create']);
    Route::get('/trucks', [TruckController::class, 'list']);
    Route::patch('/trucks/{id}', [TruckController::class, 'edit']);
    Route::get('/trucks/{id}', [TruckController::class, 'find']);
    Route::delete('/trucks/{id}', [TruckController::class, 'del']);
});

Route::middleware([JWTMiddleware::class, MaintenanceMiddleware::class])->group(function() {
    Route::post('/maintenances', [MaintenanceController::class, 'create']);
    Route::get('/maintenances', [MaintenanceController::class, 'list']);
    Route::patch('/maintenances/{id}', [MaintenanceController::class, 'edit']);
    Route::get('/maintenances/{id}', [MaintenanceController::class, 'find']);
    Route::delete('/maintenances/{id}', [MaintenanceController::class, 'del']);
    Route::get('/maintenances/charts/scatter', [MaintenanceController::class, 'chartsScatter']);
});

Route::middleware([JWTMiddleware::class, DriverMiddleware::class])->group(function() {
    Route::post('/drivers', [DriverController::class, 'create']);
    Route::get('/drivers', [DriverController::class, 'list']);
    Route::patch('/drivers/{id}', [DriverController::class, 'edit']);
    Route::get('/drivers/{id}', [DriverController::class, 'find']);
    Route::delete('/drivers/{id}', [DriverController::class, 'del']);
});