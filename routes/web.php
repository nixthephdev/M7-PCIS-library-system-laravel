<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\AuthController;

// ==========================================
// PUBLIC ROUTES (No Login Required)
// ==========================================

// 1. Landing Page (Home)
Route::get('/', function () {
    return view('welcome');
})->name('home');

// 2. Login Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// ==========================================
// PROTECTED ROUTES (Login Required)
// ==========================================
Route::middleware(['auth', 'admin'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [LibraryController::class, 'index'])->name('dashboard');

    // Inventory
    Route::prefix('inventory')->group(function () {
        Route::get('/', [LibraryController::class, 'inventory'])->name('inventory.index');
        Route::post('/add', [LibraryController::class, 'storePurchase'])->name('inventory.store');
        Route::get('/book/{id}', [LibraryController::class, 'showBook'])->name('inventory.show');
        Route::post('/book/update/{id}', [LibraryController::class, 'updateBook'])->name('inventory.update');
    });

    // Circulation
    Route::prefix('circulation')->group(function () {
        Route::get('/', [LibraryController::class, 'circulation'])->name('circulation.index');
        Route::post('/borrow', [LibraryController::class, 'borrowBook'])->name('circulation.borrow');
        Route::post('/return', [LibraryController::class, 'returnBook'])->name('circulation.return');
    });
    
    // Admin Profile
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::post('/profile', [AuthController::class, 'updateProfile'])->name('profile.update'); 
    
    // Members
    Route::prefix('users')->group(function () {
        Route::get('/', [LibraryController::class, 'usersIndex'])->name('users.index');
        Route::post('/add', [LibraryController::class, 'userStore'])->name('users.store');
        
        // NEW ROUTES
        Route::post('/update/{id}', [LibraryController::class, 'updateUser'])->name('users.update');
        Route::delete('/delete/{id}', [LibraryController::class, 'deleteUser'])->name('users.delete');
    });

});