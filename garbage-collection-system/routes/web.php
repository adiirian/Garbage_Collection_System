<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Collector\CollectionController;
use App\Http\Controllers\Public\AlertController;

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    Route::prefix('collector')->group(function () {
        Route::get('/bins', [CollectionController::class, 'index'])->name('collector.bins.index');
        Route::post('/bins/{bin}/status', [CollectionController::class, 'updateStatus'])->name('collector.bins.updateStatus');
    });
});

Route::prefix('alerts')->group(function () {
    Route::post('/', [AlertController::class, 'store'])->name('alerts.store');
});