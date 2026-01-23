<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BuyerDashboardController;
use App\Http\Controllers\SellrDashboardController;
use App\Http\Controllers\AgentDashboardController;
use App\Http\Controllers\AdminDashboardController;

use App\Livewire\Auth\Login;

/*
|--------------------------------------------------------------------------
| Public Property Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/buy', [PropertyController::class, 'buy'])->name('buy.filter');
Route::get('/sell', [PropertyController::class, 'sell'])->name('sell.filter');
Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');


/*
|--------------------------------------------------------------------------
| Authenticated CRUD Routes for Properties
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Property CRUD
    Route::get('/properties/create', [PropertyController::class, 'create'])
        ->name('properties.create');

    Route::post('/properties', [PropertyController::class, 'store'])
        ->name('properties.store');

    Route::get('/properties/{property}/edit', [PropertyController::class, 'edit'])
        ->name('properties.edit');

    Route::put('/properties/{property}', [PropertyController::class, 'update'])
        ->name('properties.update');

    Route::delete('/properties/{property}', [PropertyController::class, 'destroy'])
        ->name('properties.destroy');

});


/*
|--------------------------------------------------------------------------
| Profile Management (Single Definition)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

});


/*
|--------------------------------------------------------------------------
| Role Based Dashboards
|--------------------------------------------------------------------------
*/

Route::middleware(['auth','role:customer'])
    ->get('/buyer/dashboard', [BuyerDashboardController::class,'index'])
    ->name('buyer.dashboard');

Route::middleware(['auth','role:owner'])
    ->get('/seller/dashboard', [SellrDashboardController::class,'index'])
    ->name('seller.dashboard');

Route::middleware(['auth','role:agent'])
    ->get('/agent/dashboard', [AgentDashboardController::class,'index'])
    ->name('agent.dashboard');

// Route::middleware(['auth','role:admin'])
//     ->get('/admin/dashboard', [AdminDashboardController::class,'index'])
//     ->name('admin.dashboard');


/*
|--------------------------------------------------------------------------
| Main /dashboard entry → redirect according to role
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {

    $user = auth()->user();

    $role = $user->role;

    return match ($role) {

        'customer' => redirect()->route('buyer.dashboard'),

        'owner'    => redirect()->route('seller.dashboard'),

        'agent'    => redirect()->route('agent.dashboard'),

        'admin'    => redirect()->route('admin.dashboard'),

        default    => redirect('/dashboard'),

    };

})->middleware(['auth','verified'])->name('dashboard');


/*
|--------------------------------------------------------------------------
| Property Suggestions only for Buyer
|--------------------------------------------------------------------------
*/

Route::middleware(['auth','role:customer'])->group(function () {

    Route::get('/suggestions', [PropertyController::class,'suggestions'])
        ->name('properties.suggestions');

});


require __DIR__.'/auth.php';
