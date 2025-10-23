<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicUser\AlertController;

// Public routes (stateless - keep here)
Route::prefix('public')->group(function () {
    Route::post('report', [AlertController::class, 'report']);
});

// Analytics routes removed - now only in web.php for auth consistency