<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Collector\CollectionController;
use App\Http\Controllers\Residents\AlertController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/test', [TestController::class, 'test']);

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Password Reset Routes
Route::get('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');

Route::middleware(['auth'])->group(function () {
    // Admin routes (moved analytics here for auth consistency)
    Route::prefix('admin')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::post('bins/{bin}/{collected}', [DashboardController::class, 'updateBinCollectedStatus']);
        Route::get('bins/{bin}', [DashboardController::class, 'getBin'])->name('admin.bins.show');  // Add this for viewBinDetails

        Route::get('collector-management', [App\Http\Controllers\Admin\CollectorManagementController::class, 'index'])->name('admin.collector-management');
        Route::put('collectors/{id}', [App\Http\Controllers\Admin\CollectorManagementController::class, 'update'])->name('admin.collectors.update');
        Route::post('collectors/{id}/penalty', [App\Http\Controllers\Admin\CollectorManagementController::class, 'applyPenalty'])->name('admin.collectors.apply-penalty');
        Route::get('collectors/{id}/penalties', [App\Http\Controllers\Admin\CollectorManagementController::class, 'getPenalties'])->name('admin.collectors.penalties');
        Route::get('assignments', [App\Http\Controllers\Admin\CollectorManagementController::class, 'getAssignments'])->name('admin.assignments.index');
        Route::post('assignments', [App\Http\Controllers\Admin\CollectorManagementController::class, 'createAssignment'])->name('admin.assignments.store');
        Route::put('assignments/{id}', [App\Http\Controllers\Admin\CollectorManagementController::class, 'updateAssignment'])->name('admin.assignments.update');
        Route::delete('assignments/{id}', [App\Http\Controllers\Admin\CollectorManagementController::class, 'deleteAssignment'])->name('admin.assignments.destroy');

        Route::prefix('analytics')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\AnalyticsController::class, 'index'])
                ->name('admin.analytics'); // add this line
            Route::get('bins/summary', [App\Http\Controllers\Admin\AnalyticsController::class, 'binSummary']);
            Route::get('alerts/stats', [App\Http\Controllers\Admin\AnalyticsController::class, 'alertStats']);
            Route::get('collection/efficiency', [App\Http\Controllers\Admin\AnalyticsController::class, 'collectionEfficiency']);
            Route::get('collections/today', [App\Http\Controllers\Admin\AnalyticsController::class, 'collectionsToday']);
            Route::get('penalties/stats', [App\Http\Controllers\Admin\AnalyticsController::class, 'penaltiesStats']);
        });
    });

    Route::get('/collector/dashboard', [CollectionController::class, 'dashboard'])->name('collector.collection.index');

    Route::get('/residents/dashboard', [AlertController::class, 'dashboard'])->name('residents.alerts.index');

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

        Route::get('/profile', [CollectionController::class, 'showProfile'])->name('collector.profile');
        Route::post('/profile', [CollectionController::class, 'updateProfile'])->name('collector.profile.update');
    });
});
