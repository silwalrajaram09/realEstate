<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BuyerDashboardController;
use App\Http\Controllers\SellerDashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Seller\PropertyController as SellerPropertyController;

/*
|--------------------------------------------------------------------------
| Landing Page (Guests only)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| Public Property Routes
|--------------------------------------------------------------------------
*/

Route::get('/buy', [PropertyController::class, 'buy'])->name('buy.filter');
Route::get('/sell', [PropertyController::class, 'sell'])->name('sell.filter');
Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Profile Management
    |--------------------------------------------------------------------------
    */
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Role-Based Dashboards
    |--------------------------------------------------------------------------
    */

    // Buyer Routes
    Route::middleware('role:customer')->group(function () {
        Route::get('/buyer/dashboard', [BuyerDashboardController::class, 'index'])
            ->name('buyer.dashboard');

        Route::get('/suggestions', [PropertyController::class, 'suggestions'])
            ->name('properties.suggestions');

        Route::get('/buyer/buy', [PropertyController::class, 'buy'])
            ->name('properties.buy');
    });

    // Seller Routes
    Route::middleware('role:owner')->group(function () {
        Route::get('/seller/dashboard', [SellerDashboardController::class, 'index'])
            ->name('seller.dashboard');

        // Seller property CRUD using Seller\PropertyController
        Route::prefix('seller')->name('seller.')->group(function () {
            Route::resource('properties', SellerPropertyController::class);
        });
    });

    // Admin Routes
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
            ->name('admin.dashboard');

        // Future admin routes (manage users, properties, algorithm)
    });

});

/*
|--------------------------------------------------------------------------
| Main Dashboard Redirect (Role Router)
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    $user = auth()->user();

    return match ($user->role) {
        'customer' => redirect()->route('buyer.dashboard'),
        'owner'    => redirect()->route('seller.dashboard'),
        'admin'    => redirect()->route('admin.dashboard'),
        default    => abort(403),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Auth Routes (Laravel Breeze)
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';
