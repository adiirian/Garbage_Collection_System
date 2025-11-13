<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Residents\AlertController;

Route::middleware('auth:sanctum')->group(function () {
    // Admin routes
    Route::prefix('admin')->group(function () {
        Route::get('analytics/bin-summary', [App\Http\Controllers\Admin\AnalyticsController::class, 'binSummary']);
        Route::get('analytics/alert-stats', [App\Http\Controllers\Admin\AnalyticsController::class, 'alertStats']);
        Route::get('analytics/collection-efficiency', [App\Http\Controllers\Admin\AnalyticsController::class, 'collectionEfficiency']);
        Route::get('analytics/collections/today', [App\Http\Controllers\Admin\AnalyticsController::class, 'collectionsToday']);
        Route::get('analytics/penalties/stats', [App\Http\Controllers\Admin\AnalyticsController::class, 'penaltiesStats']);
    });

    // Collector routes
    Route::prefix('collector')->group(function () {
        Route::get('bins', [App\Http\Controllers\Collector\CollectionController::class, 'index']);
        Route::post('bins/{binId}/clean', [App\Http\Controllers\Collector\CollectionController::class, 'markCleaned']);
        Route::get('dashboard', [App\Http\Controllers\Collector\CollectionController::class, 'dashboard']);
        Route::get('profile', [App\Http\Controllers\Collector\CollectionController::class, 'profile']);
        Route::put('profile', [App\Http\Controllers\Collector\CollectionController::class, 'updateProfile']);
        Route::get('assignments', [App\Http\Controllers\Collector\CollectionController::class, 'getAssignments']);
        Route::put('assignments/{id}/status', [App\Http\Controllers\Collector\CollectionController::class, 'updateAssignmentStatus']);
    });

    // Residents routes
    Route::prefix('residents')->group(function () {
        Route::get('alerts', [App\Http\Controllers\Residents\AlertController::class, 'index']);
        Route::post('alerts', [App\Http\Controllers\Residents\AlertController::class, 'store']);
        Route::get('dashboard', [App\Http\Controllers\Residents\AlertController::class, 'dashboard']);
        Route::get('bins', [App\Http\Controllers\Residents\AlertController::class, 'getBins']);
    });
});

// Public routes (stateless - keep here)
Route::prefix('public')->group(function () {
    Route::post('report', [AlertController::class, 'store']);
    Route::get('welcome', [AlertController::class, 'welcome']);
});

// Analytics routes removed - now only in web.php for auth consistency