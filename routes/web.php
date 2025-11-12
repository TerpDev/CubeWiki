<?php

use App\Models\Tenants;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\NavigationController;
use Laravel\Sanctum\PersonalAccessToken;


Route::view('/', 'welcome')->name('home');

// Public API endpoint - Token in URL (for embedding)
Route::get('api/data/{token}', [NavigationController::class, 'byToken'])
    ->middleware(['throttle:60,1']); // Rate limit: 60 requests per minute


Route::prefix('api/tenants/{tenant}')->middleware(['auth:sanctum'])->group(function () {
    //navigation
    Route::get('navigation', [NavigationController::class, 'index']);

    // Categories
    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('categories/{category}', [CategoryController::class, 'show']);

    // Pages
    Route::get('pages', [PageController::class, 'index']);
    Route::get('pages/{page}', [PageController::class, 'show']);

    // Applications
    Route::get('applications', [ApplicationController::class, 'index']);
    Route::get('applications/{application}', [ApplicationController::class, 'show']);
});
require __DIR__.'/auth.php';
