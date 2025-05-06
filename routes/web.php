<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobVacancyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Shared Routes for Admin and Company Owner
Route::middleware(['auth', 'verified', 'role:admin,company-owner'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Job Vacancies (shared but with different access levels controlled in controller)
    Route::resource('job-vacancy', JobVacancyController::class);
    Route::post('job-vacancy/{id}/restore', [JobVacancyController::class, 'restore'])->name('job-vacancy.restore');

    // Applications (shared but with different access levels controlled in controller)
    Route::resource('application', ApplicationController::class);
    Route::post('application/{id}/restore', [ApplicationController::class, 'restore'])->name('application.restore');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Company Owner Specific Routes
Route::middleware(['auth', 'verified', 'role:company-owner'])->group(function () {
    // View Own Company Only - using existing controller methods
    Route::get('/my-company', [CompanyController::class, 'show'])->name('company-owner.company.show');
    Route::get('/my-company/edit', [CompanyController::class, 'edit'])->name('company-owner.company.edit');
    Route::put('/my-company', [CompanyController::class, 'update'])->name('company-owner.company.update');
});

// Admin Specific Routes
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    // Companies Management
    Route::resource('company', CompanyController::class);
    Route::post('/companies/{id}/restore', [CompanyController::class, 'restore'])->name('company.restore');

    // Categories Management
    Route::resource('category', CategoryController::class);
    Route::post('category/{id}/restore', [CategoryController::class, 'restore'])->name('category.restore');

    // Users Management
    Route::resource('user', UserController::class);
    Route::post('user/{id}/restore', [UserController::class, 'restore'])->name('user.restore');
});

require __DIR__ . '/auth.php';
