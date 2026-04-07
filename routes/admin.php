<?php


use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use Illuminate\Support\Facades\Route;


Route::name('admin.')->middleware('guest:admin')->group(function () {

    // SHOW LOGIN PAGE (GET)
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])
        ->name('login');

    // HANDLE LOGIN (POST)
    Route::post('/login', [AdminAuthController::class, 'login'])
        ->name('login.submit');
});



Route::name('admin.')->middleware('admin.auth')->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/users', [AdminDashboardController::class, 'users'])
        ->name('users');

    Route::match(['post', 'patch'], '/users/{user}/make-admin', [AdminDashboardController::class, 'makeAdmin'])
        ->name('users.make-admin');
    Route::match(['post', 'patch'], '/users/{user}/suspend-toggle', [AdminDashboardController::class, 'toggleSuspend'])
        ->name('users.suspend-toggle');

    Route::get('/properties', [AdminDashboardController::class, 'properties'])
        ->name('properties');
    Route::get('/analytics', [AdminDashboardController::class, 'analytics'])
        ->name('analytics');

    Route::match(['get', 'post', 'patch'], '/properties/{property}/approve', [AdminDashboardController::class, 'approveProperty'])
        ->name('properties.approve');

    Route::match(['post', 'patch'], '/properties/{property}/reject', [AdminDashboardController::class, 'rejectProperty'])
        ->name('properties.reject');
    Route::match(['post', 'patch'], '/properties/{property}/request-changes', [AdminDashboardController::class, 'requestChanges'])
        ->name('properties.request-changes');
    Route::match(['post', 'patch'], '/properties/{property}/featured-toggle', [AdminDashboardController::class, 'toggleFeatured'])
        ->name('properties.featured-toggle');

    Route::post('/logout', [AdminAuthController::class, 'logout'])
        ->name('logout');
});