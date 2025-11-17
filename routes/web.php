<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\ReportsController;

// Publicly accessible route for the welcome/landing page
Route::get('/', function () {
    return view('welcome');
});

// New public route for the About Us page
Route::get('/about-us', function () {
    return view('about');
})->name('about.us');

// Routes that require a user to be logged in and have a verified email
Route::middleware(['auth', 'verified'])->group(function () { // <-- THIS IS THE FIX

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Sales Resource routes and custom payment routes
    Route::resource('sales', SalesController::class);
    Route::get('/sales/{sale}/payment', [SalesController::class, 'recordPaymentForm'])->name('sales.payment.form');
    Route::post('/sales/{sale}/payment', [SalesController::class, 'recordPayment'])->name('sales.payment');
    
    // Standardized expense resource routes.
    Route::resource('expenses', ExpenseController::class);
    // FIX: Custom route to securely serve the expense receipt
    Route::get('/expenses/{expense}/receipt', [ExpenseController::class, 'showReceipt'])->name('expenses.receipt');

    // Budget Routes with consistent naming
    Route::get('budgets', [BudgetController::class, 'index'])->name('budgets.index');
    Route::post('budgets', [BudgetController::class, 'store'])->name('budgets.store');

    // Reports Routes with consistent naming
    Route::get('reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::get('reports/generate', [ReportsController::class, 'generate'])->name('reports.generate');
    Route::get('reports/export', [ReportsController::class, 'export'])->name('reports.export');

});

// Includes the default authentication routes (login, register, password reset, etc.)
require __DIR__.'/auth.php';