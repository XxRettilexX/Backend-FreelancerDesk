<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\EstimateController;
use App\Http\Controllers\TimeEntryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\DashboardController;

Route::get('/test-api', function () {
    return 'API FILE LOADED';
});


Route::prefix('v1')->group(function () {

    // ==== AUTH PUBLIC ====
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);

    // ==== AUTH PROTECTED ====
    Route::middleware('auth:sanctum')->group(function () {

        Route::get('/me',      [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);

        // ==== CLIENTS ====
        Route::apiResource('clients', ClientController::class);

        // ==== PROJECTS ====
        Route::apiResource('projects', ProjectController::class);

        // ==== ESTIMATES ====
        Route::apiResource('estimates', EstimateController::class);

        // ==== TIME TRACKING ====
        Route::apiResource('time', TimeEntryController::class)
            ->only(['index', 'store', 'destroy']);

        // ==== INVOICES ====
        Route::apiResource('invoices', InvoiceController::class);

        // ==== DASHBOARD ====
        Route::get('/dashboard', [DashboardController::class, 'stats']);
    });
});
