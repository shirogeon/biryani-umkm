<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderManagementController;
use App\Http\Controllers\Customer\MenuController;
use App\Http\Controllers\Customer\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    // Menu Endpoints
    Route::get('/menu', [MenuController::class, 'index']);
    Route::get('/menu/{slug}', [MenuController::class, 'show']);

    // Order Endpoints
    Route::post('/checkout', [OrderController::class, 'checkout']);
    Route::get('/orders/{code}', [OrderController::class, 'track']);
    Route::get('/orders/{code}/receipt', [OrderController::class, 'receipt']);

    // Admin API Endpoints
    Route::get('/admin/dashboard', [DashboardController::class, 'index']);
    Route::get('/admin/orders', [OrderManagementController::class, 'index']);
    Route::patch('/admin/orders/{id}/status', [OrderManagementController::class, 'updateStatus']);
});
