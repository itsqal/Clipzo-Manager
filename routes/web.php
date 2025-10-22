<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\UserController;
use App\Http\Controllers\SuperAdmin\serviceContoller;
use App\Http\Controllers\SuperAdmin\productController;
use App\Http\Controllers\SuperAdmin\CashInController;
use App\Http\Controllers\SuperAdmin\CashOutController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// SuperAdmin routes
Route::middleware(['auth', 'superadmin'])
    ->prefix('superadmin')
    ->as('superadmin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/cash-in', [CashInController::class, 'index'])->name('cash-in');

        Route::get('/cash-out', [CashOutController::class, 'index'])->name('cash-out');

        Route::get('/users', [UserController::class, 'index'])->name('users');

        Route::get('/services', [serviceContoller::class, 'index'])->name('services');

        Route::get('/products', [productController::class, 'index'])->name('products');

    });

// Admin routes
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {

        Route::get('/home', [HomeController::class, 'index'])->name('home');
    });