<?php


use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

// =====================
// Admin Auth (Guest)
// =====================
Route::prefix('admin')->name('admin.')->middleware('guest:admin')->group(function () {

    // SHOW LOGIN PAGE (GET)
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])
        ->name('login');

    // HANDLE LOGIN (POST)
    Route::post('/login', [AdminAuthController::class, 'login'])
        ->name('login.submit');
});


// =====================
// Admin Protected
// =====================
Route::prefix('admin')->name('admin.')->middleware('admin.auth')->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/users', [AdminDashboardController::class, 'users'])
        ->name('users');

    Route::patch('/users/{user}/make-admin', [AdminDashboardController::class, 'makeAdmin'])
        ->name('users.make-admin');

    Route::get('/properties', [AdminDashboardController::class, 'properties'])
        ->name('properties');

    Route::patch('/properties/{property}/approve', [AdminDashboardController::class, 'approveProperty'])
        ->name('properties.approve');

    Route::patch('/properties/{property}/reject', [AdminDashboardController::class, 'rejectProperty'])
        ->name('properties.reject');

    Route::post('/logout', [AdminAuthController::class, 'logout'])
        ->name('logout');
});