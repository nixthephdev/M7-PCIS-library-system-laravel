<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LibraryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Dashboard / Home
Route::get('/', [LibraryController::class, 'index'])->name('dashboard');

// 2. Inventory Management Routes (Stock In & Catalog)
Route::prefix('inventory')->group(function () {
    // Show list of books
    Route::get('/', [LibraryController::class, 'inventory'])->name('inventory.index');
    // Process the "Purchase/Stock In" form
    Route::post('/add', [LibraryController::class, 'storePurchase'])->name('inventory.store');
    Route::get('/book/{id}', [LibraryController::class, 'showBook'])->name('inventory.show');
});

// 3. Circulation Routes (In and Out Registration)
Route::prefix('circulation')->group(function () {
    // Show the Borrow/Return interface
    Route::get('/', [LibraryController::class, 'circulation'])->name('circulation.index');
    
    // Process Borrowing (Stock Out)
    Route::post('/borrow', [LibraryController::class, 'borrowBook'])->name('circulation.borrow');
    
    // Process Returning (Stock In)
    Route::post('/return', [LibraryController::class, 'returnBook'])->name('circulation.return');

    Route::prefix('users')->group(function () {
    Route::get('/', [LibraryController::class, 'usersIndex'])->name('users.index');
    Route::post('/add', [LibraryController::class, 'userStore'])->name('users.store');
});
});