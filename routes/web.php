<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Public
Route::middleware('guest')->group(function () {
    Route::get('/',         [AuthController::class, 'showLogin'])->name('home');
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
});

// Authenticated
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Expenses (own records)
    Route::get('/expenses',           [ExpenseController::class, 'index'])->name('expenses.index');
    Route::post('/expenses',          [ExpenseController::class, 'store'])->name('expenses.store');
    Route::get('/expenses/{expense}/edit',   [ExpenseController::class, 'edit'])->name('expenses.edit');
    Route::put('/expenses/{expense}',        [ExpenseController::class, 'update'])->name('expenses.update');
    Route::delete('/expenses/{expense}',     [ExpenseController::class, 'destroy'])->name('expenses.destroy');

    // Profile
    Route::get('/profile',  [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile',  [ProfileController::class, 'update'])->name('profile.update');

    // Users (admin only)
    Route::middleware(['auth', 'can:admin-only'])->group(function () {
    Route::get('/users',              [UserController::class, 'index'])->name('users.index');
    Route::post('/users',             [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit',  [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}',       [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}',    [UserController::class, 'destroy'])->name('users.destroy');
    });
});