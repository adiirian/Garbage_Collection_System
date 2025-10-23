<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Collector\CollectionController;
use App\Http\Controllers\Public\AlertController;

Route::middleware('auth:api')->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index']);
    
    Route::prefix('collector')->group(function () {
        Route::get('/bins', [CollectionController::class, 'index']);
        Route::post('/bins/{bin}/status', [CollectionController::class, 'updateStatus']);
    });
});

Route::prefix('public')->group(function () {
    Route::post('/alerts', [AlertController::class, 'store']);
    Route::get('/bins/{bin}/status', [AlertController::class, 'show']);
});