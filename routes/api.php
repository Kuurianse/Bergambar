<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use App\Http\Controllers\Api\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\Admin\ArtistController as AdminArtistController;
use App\Http\Controllers\Api\Admin\CommissionController as AdminCommissionController;
use App\Http\Controllers\Api\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Api\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Api\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Api\Admin\DashboardController as AdminDashboardController; // Added DashboardController

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Admin API routes
Route::middleware('auth:sanctum')->prefix('v1/admin')->name('admin.api.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard.index');

    // User Management
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    // Add other admin user routes here later (e.g., update, store, destroy)
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{user}/promote-to-artist', [AdminUserController::class, 'promoteToArtist'])->name('users.promote');

    // Artist Management
    Route::get('/artists', [AdminArtistController::class, 'index'])->name('artists.index');
    Route::get('/artists/{artist}', [AdminArtistController::class, 'show'])->name('artists.show');
    Route::put('/artists/{artist}', [AdminArtistController::class, 'update'])->name('artists.update');
    Route::put('/artists/{artist}/toggle-verification', [AdminArtistController::class, 'toggleVerification'])->name('artists.toggleVerification');

    // Commission Management
    Route::get('/commissions', [AdminCommissionController::class, 'index'])->name('commissions.index');
    Route::get('/commissions/{commission}', [AdminCommissionController::class, 'show'])->name('commissions.show');

    // Category Management
    Route::apiResource('categories', AdminCategoryController::class);

    // Service Management
    Route::get('/services', [AdminServiceController::class, 'index'])->name('services.index');

    // Order Management
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    // Route::put('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
});

// Note: Login and Logout routes for SPA are typically handled by Laravel's web routes
// (e.g., from Auth::routes()) and Sanctum's stateful authentication.
// The Next.js app will POST to /login and /logout (web routes).
// The /sanctum/csrf-cookie route is also a web route.
