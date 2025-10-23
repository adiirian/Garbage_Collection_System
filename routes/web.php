<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Collector\CollectionController;
use App\Http\Controllers\PublicUser\AlertController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/test', [TestController::class, 'test']);

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::middleware(['auth'])->group(function () {
    // Admin routes (moved analytics here for auth consistency)
    Route::prefix('admin')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::post('bins/{bin}/{level}', [DashboardController::class, 'updateBinLevel']);
        Route::get('bins/{bin}', [DashboardController::class, 'getBin'])->name('admin.bins.show');  // Add this for viewBinDetails

        Route::prefix('analytics')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\AnalyticsController::class, 'index'])
                ->name('admin.analytics'); // add this line
            Route::get('bins/summary', [App\Http\Controllers\Admin\AnalyticsController::class, 'binSummary']);
            Route::get('alerts/stats', [App\Http\Controllers\Admin\AnalyticsController::class, 'alertStats']);
            Route::get('collection/efficiency', [App\Http\Controllers\Admin\AnalyticsController::class, 'collectionEfficiency']);
            Route::get('collections/today', [App\Http\Controllers\Admin\AnalyticsController::class, 'collectionsToday']);
        });
    });

    Route::get('/collector/dashboard', [CollectionController::class, 'dashboard'])->name('collector.collection.index');

    Route::get('/public/dashboard', [AlertController::class, 'dashboard'])->name('public.alerts.index');

    Route::post('/alerts', [AlertController::class, 'store'])->name('alerts.store');

    Route::prefix('collector')->group(function () {
        // Add this missing GET route for fetching bin data (used by edit button)
        Route::get('/bins/{bin}', [CollectionController::class, 'getBin'])->name('collector.bins.show');

        // Your existing routes (keep as-is)
        Route::get('/bins', [CollectionController::class, 'index'])->name('collector.bins.index');
        Route::get('/bins/create', [CollectionController::class, 'createBinForm'])->name('collector.bins.create');
        Route::get('/bins/{bin}/edit', [CollectionController::class, 'editBin'])->name('collector.bins.edit');
        Route::post('/bins', [CollectionController::class, 'createBin'])->name('collector.bins.store');
        Route::delete('/bins/{bin}', [CollectionController::class, 'deleteBin'])->name('collector.bins.destroy');
        Route::post('/bins/{bin}/status', [CollectionController::class, 'updateStatus'])->name('collector.bins.updateStatus');
        Route::post('/bins/{bin}/clean', [CollectionController::class, 'markCleaned'])->name('collector.bins.markCleaned');
        Route::put('/bins/{bin}', [CollectionController::class, 'updateBin'])->name('collector.bins.update');
    });
});